namespace SystemPOS.Application;

public sealed record ProductDto(
    int Id,
    int CompanyId,
    string Sku,
    string? Barcode,
    string Name,
    decimal Cost,
    decimal Price,
    decimal MinimumStock,
    bool IsActive);

public sealed record CreateProductRequest(
    int CompanyId,
    string Sku,
    string? Barcode,
    string Name,
    decimal Cost,
    decimal Price,
    decimal MinimumStock);

public sealed record AdjustInventoryRequest(
    int CompanyId,
    int BranchId,
    int ProductId,
    decimal QuantityDelta,
    string? Notes);

public sealed record InventoryStockDto(
    int ProductId,
    string ProductName,
    decimal Quantity,
    decimal MinimumStock);

public sealed record CreateSaleItemRequest(int ProductId, decimal Quantity);

public sealed record CreateSaleRequest(
    int CompanyId,
    int BranchId,
    IReadOnlyCollection<CreateSaleItemRequest> Items);

public sealed record SaleResult(
    long SaleId,
    string SaleNumber,
    decimal Subtotal,
    decimal Tax,
    decimal Total);

public interface IProductService
{
    Task<IReadOnlyCollection<ProductDto>> GetAsync(int companyId, CancellationToken cancellationToken = default);
    Task<ProductDto> CreateAsync(CreateProductRequest request, CancellationToken cancellationToken = default);
}

public interface IInventoryService
{
    Task<IReadOnlyCollection<InventoryStockDto>> GetAsync(int companyId, int branchId, CancellationToken cancellationToken = default);
    Task<InventoryStockDto> AdjustAsync(AdjustInventoryRequest request, CancellationToken cancellationToken = default);
}

public interface ISaleService
{
    Task<SaleResult> CreateAsync(CreateSaleRequest request, CancellationToken cancellationToken = default);
}
