using Microsoft.EntityFrameworkCore;
using SystemPOS.Application;
using SystemPOS.Domain;
using SystemPOS.Infrastructure.Persistence;

namespace SystemPOS.Infrastructure.Services;

public sealed class ProductService(SystemPosDbContext db) : IProductService
{
    public async Task<IReadOnlyCollection<ProductDto>> GetAsync(int companyId, CancellationToken cancellationToken = default)
        => await db.Products
            .AsNoTracking()
            .Where(x => x.CompanyId == companyId)
            .OrderBy(x => x.Name)
            .Select(x => new ProductDto(x.Id, x.CompanyId, x.Sku, x.Barcode, x.Name, x.Cost, x.Price, x.MinimumStock, x.IsActive))
            .ToListAsync(cancellationToken);

    public async Task<ProductDto> CreateAsync(CreateProductRequest request, CancellationToken cancellationToken = default)
    {
        if (request.CompanyId <= 0) throw new ArgumentException("CompanyId is required.");
        if (string.IsNullOrWhiteSpace(request.Sku)) throw new ArgumentException("SKU is required.");
        if (string.IsNullOrWhiteSpace(request.Name)) throw new ArgumentException("Product name is required.");
        if (request.Price < 0 || request.Cost < 0) throw new ArgumentException("Price and cost cannot be negative.");

        var exists = await db.Products.AnyAsync(
            x => x.CompanyId == request.CompanyId && x.Sku == request.Sku,
            cancellationToken);
        if (exists) throw new InvalidOperationException("A product with this SKU already exists for the company.");

        var product = new Product
        {
            CompanyId = request.CompanyId,
            Sku = request.Sku.Trim(),
            Barcode = string.IsNullOrWhiteSpace(request.Barcode) ? null : request.Barcode.Trim(),
            Name = request.Name.Trim(),
            Cost = request.Cost,
            Price = request.Price,
            MinimumStock = request.MinimumStock,
            IsActive = true
        };

        db.Products.Add(product);
        await db.SaveChangesAsync(cancellationToken);

        return new ProductDto(product.Id, product.CompanyId, product.Sku, product.Barcode, product.Name,
            product.Cost, product.Price, product.MinimumStock, product.IsActive);
    }
}

public sealed class InventoryService(SystemPosDbContext db) : IInventoryService
{
    public async Task<IReadOnlyCollection<InventoryStockDto>> GetAsync(int companyId, int branchId, CancellationToken cancellationToken = default)
        => await db.InventoryStocks
            .AsNoTracking()
            .Where(x => x.CompanyId == companyId && x.BranchId == branchId)
            .OrderBy(x => x.Product.Name)
            .Select(x => new InventoryStockDto(x.ProductId, x.Product.Name, x.Quantity, x.Product.MinimumStock))
            .ToListAsync(cancellationToken);

    public async Task<InventoryStockDto> AdjustAsync(AdjustInventoryRequest request, CancellationToken cancellationToken = default)
    {
        await using var tx = await db.Database.BeginTransactionAsync(cancellationToken);

        var product = await db.Products.SingleOrDefaultAsync(
            x => x.Id == request.ProductId && x.CompanyId == request.CompanyId && x.IsActive,
            cancellationToken) ?? throw new KeyNotFoundException("Product not found.");

        var branchExists = await db.Branches.AnyAsync(
            x => x.Id == request.BranchId && x.CompanyId == request.CompanyId && x.IsActive,
            cancellationToken);
        if (!branchExists) throw new KeyNotFoundException("Branch not found.");

        var stock = await db.InventoryStocks.SingleOrDefaultAsync(
            x => x.CompanyId == request.CompanyId && x.BranchId == request.BranchId && x.ProductId == request.ProductId,
            cancellationToken);

        if (stock is null)
        {
            stock = new InventoryStock
            {
                CompanyId = request.CompanyId,
                BranchId = request.BranchId,
                ProductId = request.ProductId,
                Quantity = 0m
            };
            db.InventoryStocks.Add(stock);
        }

        var before = stock.Quantity;
        var after = before + request.QuantityDelta;
        if (after < 0) throw new InvalidOperationException("Inventory cannot become negative.");

        stock.Quantity = after;
        stock.UpdatedAtUtc = DateTime.UtcNow;

        db.InventoryMovements.Add(new InventoryMovement
        {
            CompanyId = request.CompanyId,
            BranchId = request.BranchId,
            ProductId = request.ProductId,
            Type = InventoryMovementType.Adjustment,
            Quantity = request.QuantityDelta,
            QuantityBefore = before,
            QuantityAfter = after,
            Notes = request.Notes
        });

        await db.SaveChangesAsync(cancellationToken);
        await tx.CommitAsync(cancellationToken);

        return new InventoryStockDto(product.Id, product.Name, stock.Quantity, product.MinimumStock);
    }
}

public sealed class SaleService(SystemPosDbContext db) : ISaleService
{
    public async Task<SaleResult> CreateAsync(CreateSaleRequest request, CancellationToken cancellationToken = default)
    {
        if (request.Items.Count == 0) throw new ArgumentException("A sale must contain at least one item.");
        if (request.Items.Any(x => x.Quantity <= 0)) throw new ArgumentException("Sale quantities must be greater than zero.");

        var branchExists = await db.Branches.AnyAsync(
            x => x.Id == request.BranchId && x.CompanyId == request.CompanyId && x.IsActive,
            cancellationToken);
        if (!branchExists) throw new KeyNotFoundException("Branch not found.");

        var normalizedItems = request.Items
            .GroupBy(x => x.ProductId)
            .Select(g => new CreateSaleItemRequest(g.Key, g.Sum(x => x.Quantity)))
            .ToList();

        var productIds = normalizedItems.Select(x => x.ProductId).ToArray();
        var products = await db.Products
            .Where(x => x.CompanyId == request.CompanyId && x.IsActive && productIds.Contains(x.Id))
            .ToDictionaryAsync(x => x.Id, cancellationToken);

        if (products.Count != productIds.Length) throw new KeyNotFoundException("One or more products were not found.");

        await using var tx = await db.Database.BeginTransactionAsync(cancellationToken);

        var sale = new Sale
        {
            CompanyId = request.CompanyId,
            BranchId = request.BranchId,
            SaleNumber = $"V-{DateTime.UtcNow:yyyyMMddHHmmssfff}",
            Status = SaleStatus.Completed
        };

        foreach (var item in normalizedItems)
        {
            var product = products[item.ProductId];
            var stock = await db.InventoryStocks.SingleOrDefaultAsync(
                x => x.CompanyId == request.CompanyId && x.BranchId == request.BranchId && x.ProductId == item.ProductId,
                cancellationToken) ?? throw new InvalidOperationException($"No inventory exists for product '{product.Name}'.");

            if (stock.Quantity < item.Quantity)
                throw new InvalidOperationException($"Insufficient inventory for product '{product.Name}'.");

            var before = stock.Quantity;
            stock.Quantity -= item.Quantity;
            stock.UpdatedAtUtc = DateTime.UtcNow;

            var lineTotal = decimal.Round(product.Price * item.Quantity, 2, MidpointRounding.AwayFromZero);
            sale.Items.Add(new SaleItem
            {
                ProductId = product.Id,
                ProductName = product.Name,
                Quantity = item.Quantity,
                UnitPrice = product.Price,
                LineTotal = lineTotal
            });

            db.InventoryMovements.Add(new InventoryMovement
            {
                CompanyId = request.CompanyId,
                BranchId = request.BranchId,
                ProductId = product.Id,
                Type = InventoryMovementType.Sale,
                Quantity = -item.Quantity,
                QuantityBefore = before,
                QuantityAfter = stock.Quantity,
                Reference = sale.SaleNumber
            });
        }

        sale.Subtotal = sale.Items.Sum(x => x.LineTotal);
        sale.Tax = 0m;
        sale.Total = sale.Subtotal + sale.Tax;

        db.Sales.Add(sale);
        await db.SaveChangesAsync(cancellationToken);
        await tx.CommitAsync(cancellationToken);

        return new SaleResult(sale.Id, sale.SaleNumber, sale.Subtotal, sale.Tax, sale.Total);
    }
}
