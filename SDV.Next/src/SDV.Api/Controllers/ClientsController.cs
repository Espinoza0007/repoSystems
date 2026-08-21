using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using Microsoft.AspNetCore.Authorization;
using Microsoft.AspNetCore.Mvc;
using SDV.Application.Clients;
using SDV.Contracts.Clients;

namespace SDV.Api.Controllers;

[ApiController, Authorize, Route("api/v1/clients")]
public sealed class ClientsController(IClientRepository clients) : ControllerBase
{
    [HttpGet]
    [ProducesResponseType<ClientPage>(StatusCodes.Status200OK)]
    public async Task<ActionResult<ClientPage>> List([FromQuery] string? search, [FromQuery] int page = 1, [FromQuery] int pageSize = 25, CancellationToken cancellationToken = default)
    {
        if (!User.HasClaim("permission", "clients.view")) return Forbid();
        var subject = User.FindFirst(JwtRegisteredClaimNames.Sub)?.Value ?? User.FindFirst(ClaimTypes.NameIdentifier)?.Value;
        if (!int.TryParse(subject, out var userId)) return Unauthorized();
        page = Math.Max(1, page);
        pageSize = Math.Clamp(pageSize, 10, 100);
        return Ok(await clients.ListForUserAsync(userId, search, page, pageSize, cancellationToken));
    }
}
