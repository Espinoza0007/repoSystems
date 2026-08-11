namespace SystemPOS.Domain;

public sealed class Company
{
    public int Id { get; set; }
    public string Name { get; set; } = string.Empty;
    public string? TaxId { get; set; }
    public bool IsActive { get; set; } = true;
    public DateTime CreatedAtUtc { get; set; } = DateTime.UtcNow;

    public ICollection<Branch> Branches { get; set; } = new List<Branch>();
    public ICollection<Product> Products { get; set; } = new List<Product>();
}

public sealed class Branch
{
    public int Id { get; set; }
    public int CompanyId { get; set; }
    public string Name { get; set; } = string.Empty;
    public string? Address { get; set; }
    public bool IsActive { get; set; } = true;

    public Company Company { get; set; } = null!;
    public ICollection<InventoryStock> Inventory { get; set; } = new List<InventoryStock>();
}

public sealed class Product
{
    public int Id { get; set; }
    public int CompanyId { get; set; }
    public string Sku { get; set; } = string.Empty;
    public string? Barcode { get; set; }
    public string Name { get; set; } = string.Empty;
    public decimal Cost { get; set; }
    public decimal Price { get; set; }
    public decimal MinimumStock { get; set; }
    public bool IsActive { get; set; } = true;
    public DateTime CreatedAtUtc { get; set; } = DateTime.UtcNow;

    public Company Company { get; set; } = null!;
}

public sealed class InventoryStock
{
    public long Id { get; set; }
    public int CompanyId { get; set; }
    public int BranchId { get; set; }
    public int ProductId { get; set; }
    public decimal Quantity { get; set; }
    public DateTime UpdatedAtUtc { get; set; } = DateTime.UtcNow;

    public Branch Branch { get; set; } = null!;
    public Product Product { get; set; } = null!;
}

public enum InventoryMovementType
{
    Initial = 1,
    Purchase = 2,
    Adjustment = 3,
    Sale = 4,
    Return = 5
}

public sealed class InventoryMovement
{
    public long Id { get; set; }
    public int CompanyId { get; set; }
    public int BranchId { get; set; }
    public int ProductId { get; set; }
    public InventoryMovementType Type { get; set; }
    public decimal Quantity { get; set; }
    public decimal QuantityBefore { get; set; }
    public decimal QuantityAfter { get; set; }
    public string? Reference { get; set; }
    public string? Notes { get; set; }
    public DateTime CreatedAtUtc { get; set; } = DateTime.UtcNow;
}

public enum SaleStatus
{
    Completed = 1,
    Cancelled = 2
}

public sealed class Sale
{
    public long Id { get; set; }
    public int CompanyId { get; set; }
    public int BranchId { get; set; }
    public string SaleNumber { get; set; } = string.Empty;
    public SaleStatus Status { get; set; } = SaleStatus.Completed;
    public decimal Subtotal { get; set; }
    public decimal Tax { get; set; }
    public decimal Total { get; set; }
    public DateTime CreatedAtUtc { get; set; } = DateTime.UtcNow;

    public ICollection<SaleItem> Items { get; set; } = new List<SaleItem>();
}

public sealed class SaleItem
{
    public long Id { get; set; }
    public long SaleId { get; set; }
    public int ProductId { get; set; }
    public string ProductName { get; set; } = string.Empty;
    public decimal Quantity { get; set; }
    public decimal UnitPrice { get; set; }
    public decimal LineTotal { get; set; }

    public Sale Sale { get; set; } = null!;
}
