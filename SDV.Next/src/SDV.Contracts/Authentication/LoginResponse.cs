namespace SDV.Contracts.Authentication;
public sealed record LoginResponse(string AccessToken, DateTimeOffset ExpiresAt, UserSummary User);
public sealed record UserSummary(int Id, string Username, string DisplayName, string Role, bool MustChangePassword, IReadOnlyCollection<string> Permissions, UserContext Context);
public sealed record UserContext(int RouteId, string RouteName, int ChannelId, string ChannelName, int DistributorId, string DistributorName, int DivisionId, int CountryId, string CountryName);
