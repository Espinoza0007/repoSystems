using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.RateLimiting;
using SDV.Application.Authentication;
using SDV.Contracts.Authentication;

namespace SDV.Api.Controllers;

[ApiController, Route("api/v1/auth")]
public sealed class AuthController(IAuthenticationService authentication) : ControllerBase
{
    [HttpPost("login"), EnableRateLimiting("auth")]
    [ProducesResponseType<LoginResponse>(StatusCodes.Status200OK)]
    [ProducesResponseType(StatusCodes.Status401Unauthorized)]
    public async Task<ActionResult<LoginResponse>> Login(LoginRequest request, CancellationToken cancellationToken)
    {
        var result = await authentication.LoginAsync(request, cancellationToken);
        return result is null ? Unauthorized(new ProblemDetails { Title = "Credenciales inválidas" }) : Ok(result);
    }
}

