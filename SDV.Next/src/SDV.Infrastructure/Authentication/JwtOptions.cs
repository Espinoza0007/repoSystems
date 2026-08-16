namespace SDV.Infrastructure.Authentication;
public sealed class JwtOptions { public const string Section = "Jwt"; public required string Key { get; init; } public required string Issuer { get; init; } public required string Audience { get; init; } public int Minutes { get; init; } = 15; }

