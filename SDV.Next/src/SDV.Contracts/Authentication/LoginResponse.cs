namespace SDV.Contracts.Authentication;
public sealed record LoginResponse(string AccessToken, DateTimeOffset ExpiresAt, UserSummary User);
public sealed record UserSummary(int Id, string Username, string DisplayName, IReadOnlyCollection<string> Permissions);

