using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;
using Microsoft.Extensions.Options;
using Microsoft.IdentityModel.Tokens;
using SDV.Application.Authentication;
using SDV.Contracts.Authentication;
using SDV.Domain.Users;

namespace SDV.Infrastructure.Authentication;

public sealed class JwtTokenService(IOptions<JwtOptions> options) : ITokenService
{
    public LoginResponse Create(User user)
    {
        var settings = options.Value;
        var expires = DateTimeOffset.UtcNow.AddMinutes(settings.Minutes);
        var claims = new List<Claim> { new(JwtRegisteredClaimNames.Sub, user.Id.ToString()), new(JwtRegisteredClaimNames.UniqueName, user.Username), new(ClaimTypes.Name, user.DisplayName) };
        claims.AddRange(user.Permissions.Select(permission => new Claim("permission", permission)));
        var credentials = new SigningCredentials(new SymmetricSecurityKey(Encoding.UTF8.GetBytes(settings.Key)), SecurityAlgorithms.HmacSha256);
        var token = new JwtSecurityToken(settings.Issuer, settings.Audience, claims, expires: expires.UtcDateTime, signingCredentials: credentials);
        var context = new UserContext(user.RouteId, user.RouteName, user.ChannelId, user.ChannelName, user.DistributorId, user.DistributorName, user.DivisionId, user.CountryId, user.CountryName);
        return new LoginResponse(new JwtSecurityTokenHandler().WriteToken(token), expires, new UserSummary(user.Id, user.Username, user.DisplayName, user.RoleName, user.MustChangePassword, user.Permissions, context));
    }
}
