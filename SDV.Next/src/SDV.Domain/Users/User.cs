namespace SDV.Domain.Users;

public sealed class User
{
    public int Id { get; init; }
    public required string Username { get; init; }
    public required string DisplayName { get; init; }
    public required string PasswordHash { get; init; }
    public bool IsActive { get; init; }
    public IReadOnlyCollection<string> Permissions { get; init; } = [];
}

