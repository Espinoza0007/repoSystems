using Microsoft.EntityFrameworkCore;
using SystemPOS.Application;
using SystemPOS.Infrastructure.Persistence;
using SystemPOS.Infrastructure.Services;

var builder = WebApplication.CreateBuilder(args);

builder.Services.AddOpenApi();
builder.Services.AddProblemDetails();

builder.Services.AddDbContext<SystemPosDbContext>(options =>
    options.UseSqlServer(builder.Configuration.GetConnectionString("SystemPOS")));

builder.Services.AddScoped<IProductService, ProductService>();
builder.Services.AddScoped<IInventoryService, InventoryService>();
builder.Services.AddScoped<ISaleService, SaleService>();

var app = builder.Build();

app.UseExceptionHandler();

if (app.Environment.IsDevelopment())
{
    app.MapOpenApi();
}

app.MapGet("/health", () => Results.Ok(new
{
    status = "ok",
    service = "SystemPOS.Api",
    utc = DateTime.UtcNow
}));

var products = app.MapGroup("/api/products").WithTags("Products");
products.MapGet("/", async (int companyId, IProductService service, CancellationToken ct)
    => Results.Ok(await service.GetAsync(companyId, ct)));
products.MapPost("/", async (CreateProductRequest request, IProductService service, CancellationToken ct) =>
{
    var result = await service.CreateAsync(request, ct);
    return Results.Created($"/api/products/{result.Id}", result);
});

var inventory = app.MapGroup("/api/inventory").WithTags("Inventory");
inventory.MapGet("/", async (int companyId, int branchId, IInventoryService service, CancellationToken ct)
    => Results.Ok(await service.GetAsync(companyId, branchId, ct)));
inventory.MapPost("/adjustments", async (AdjustInventoryRequest request, IInventoryService service, CancellationToken ct)
    => Results.Ok(await service.AdjustAsync(request, ct)));

var sales = app.MapGroup("/api/sales").WithTags("Sales");
sales.MapPost("/", async (CreateSaleRequest request, ISaleService service, CancellationToken ct) =>
{
    var result = await service.CreateAsync(request, ct);
    return Results.Created($"/api/sales/{result.SaleId}", result);
});

app.Run();
