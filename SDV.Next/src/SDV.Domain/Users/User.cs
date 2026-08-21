namespace SDV.Domain.Users;

public sealed class User
{
    public int Id { get; init; }
    public required string Username { get; init; }
    public required string DisplayName { get; init; }
    public required string PasswordHash { get; init; }
    public bool IsActive { get; init; }
    public int RoleId { get; init; }
    public required string RoleName { get; init; }
    public int RouteId { get; init; }
    public required string RouteName { get; init; }
    public int ChannelId { get; init; }
    public required string ChannelName { get; init; }
    public int DistributorId { get; init; }
    public required string DistributorName { get; init; }
    public int DivisionId { get; init; }
    public int CountryId { get; init; }
    public required string CountryName { get; init; }
    public bool MustChangePassword { get; init; }
    public IReadOnlyCollection<string> Permissions => Security.LegacyRolePermissions.ForRole(RoleId);
}
