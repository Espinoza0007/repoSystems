using System.ComponentModel.DataAnnotations;

namespace SystemPOS.Models;

public class Product
{
    public int Id { get; set; }

    [Required, StringLength(120)]
    public string Name { get; set; } = string.Empty;

    [StringLength(50)]
    public string? Barcode { get; set; }

    [Range(0, 999999999)]
    public decimal Price { get; set; }

    [Range(0, 999999999)]
    public decimal Stock { get; set; }

    public bool IsActive { get; set; } = true;
    public DateTime CreatedAtUtc { get; set; } = DateTime.UtcNow;
}
