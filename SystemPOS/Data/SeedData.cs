using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using SystemPOS.Models;

namespace SystemPOS.Data;

public static class SeedData
{
    public static async Task InitializeAsync(IServiceProvider services, IHostEnvironment environment)
    {
        using var scope = services.CreateScope();
        var context = scope.ServiceProvider.GetRequiredService<ApplicationDbContext>();
        var roleManager = scope.ServiceProvider.GetRequiredService<RoleManager<IdentityRole>>();
        var userManager = scope.ServiceProvider.GetRequiredService<UserManager<ApplicationUser>>();

        await context.Database.EnsureCreatedAsync();

        foreach (var role in new[] { "Administrator", "Cashier" })
        {
            if (!await roleManager.RoleExistsAsync(role))
                await roleManager.CreateAsync(new IdentityRole(role));
        }

        if (!environment.IsDevelopment())
            return;

        const string adminEmail = "admin@systempos.local";
        const string adminPassword = "Admin123!";

        var admin = await userManager.FindByEmailAsync(adminEmail);
        if (admin is null)
        {
            admin = new ApplicationUser
            {
                UserName = adminEmail,
                Email = adminEmail,
                FullName = "Administrador SystemPOS",
                EmailConfirmed = true,
                IsActive = true
            };

            var result = await userManager.CreateAsync(admin, adminPassword);
            if (!result.Succeeded)
                throw new InvalidOperationException(string.Join(" | ", result.Errors.Select(x => x.Description)));

            await userManager.AddToRoleAsync(admin, "Administrator");
        }
    }
}
