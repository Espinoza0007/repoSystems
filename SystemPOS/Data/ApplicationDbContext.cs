using Microsoft.AspNetCore.Identity.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore;
using SystemPOS.Models;

namespace SystemPOS.Data;

public class ApplicationDbContext : IdentityDbContext<ApplicationUser>
{
    public ApplicationDbContext(DbContextOptions<ApplicationDbContext> options) : base(options)
    {
    }

    public DbSet<Product> Products => Set<Product>();

    protected override void OnModelCreating(ModelBuilder builder)
    {
        base.OnModelCreating(builder);

        builder.Entity<ApplicationUser>()
            .Property(x => x.FullName)
            .HasMaxLength(150);

        builder.Entity<Product>(entity =>
        {
            entity.Property(x => x.Name).HasMaxLength(120).IsRequired();
            entity.Property(x => x.Barcode).HasMaxLength(50);
            entity.Property(x => x.Price).HasPrecision(18, 2);
            entity.Property(x => x.Stock).HasPrecision(18, 3);
            entity.HasIndex(x => x.Barcode)
                .IsUnique()
                .HasFilter("[Barcode] IS NOT NULL");
        });
    }
}
