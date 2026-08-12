using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Identity;
using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using SystemPOS.Data;
using SystemPOS.Models;

namespace SystemPOS.Controllers;

[Authorize]
public class HomeController : Controller
{
    private readonly ApplicationDbContext _db;
    private readonly UserManager<ApplicationUser> _userManager;

    public HomeController(ApplicationDbContext db, UserManager<ApplicationUser> userManager)
    {
        _db = db;
        _userManager = userManager;
    }

    public async Task<IActionResult> Index()
    {
        ViewBag.ProductCount = await _db.Products.CountAsync(x => x.IsActive);
        ViewBag.UserCount = await _userManager.Users.CountAsync(x => x.IsActive);
        ViewBag.LowStockCount = await _db.Products.CountAsync(x => x.IsActive && x.Stock <= 5);
        return View();
    }
}
