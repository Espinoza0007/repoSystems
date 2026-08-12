using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SystemPOS.Data;
using SystemPOS.Models;

namespace SystemPOS.Controllers;

[Authorize]
public class ProductsController : Controller
{
    private readonly ApplicationDbContext _db;

    public ProductsController(ApplicationDbContext db)
    {
        _db = db;
    }

    public async Task<IActionResult> Index(string? search)
    {
        var query = _db.Products.AsNoTracking().AsQueryable();
        if (!string.IsNullOrWhiteSpace(search))
            query = query.Where(x => x.Name.Contains(search) || (x.Barcode != null && x.Barcode.Contains(search)));

        ViewBag.Search = search;
        return View(await query.OrderBy(x => x.Name).ToListAsync());
    }

    [HttpGet]
    [Authorize(Roles = "Administrator")]
    public IActionResult Create() => View(new Product());

    [HttpPost]
    [ValidateAntiForgeryToken]
    [Authorize(Roles = "Administrator")]
    public async Task<IActionResult> Create(Product model)
    {
        if (!ModelState.IsValid)
            return View(model);

        _db.Products.Add(model);
        await _db.SaveChangesAsync();
        TempData["Success"] = "Producto creado correctamente.";
        return RedirectToAction(nameof(Index));
    }

    [HttpGet]
    [Authorize(Roles = "Administrator")]
    public async Task<IActionResult> Edit(int id)
    {
        var product = await _db.Products.FindAsync(id);
        return product is null ? NotFound() : View(product);
    }

    [HttpPost]
    [ValidateAntiForgeryToken]
    [Authorize(Roles = "Administrator")]
    public async Task<IActionResult> Edit(Product model)
    {
        if (!ModelState.IsValid)
            return View(model);

        var product = await _db.Products.FindAsync(model.Id);
        if (product is null)
            return NotFound();

        product.Name = model.Name;
        product.Barcode = model.Barcode;
        product.Price = model.Price;
        product.Stock = model.Stock;
        product.IsActive = model.IsActive;
        await _db.SaveChangesAsync();

        TempData["Success"] = "Producto actualizado correctamente.";
        return RedirectToAction(nameof(Index));
    }
}
