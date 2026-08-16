using Microsoft.AspNetCore.Identity;
using SDV.Application.Authentication;
using SDV.Domain.Users;
namespace SDV.Infrastructure.Authentication;
public sealed class PasswordVerifier : IPasswordVerifier
{
    private readonly PasswordHasher<User> _hasher = new();
    public bool Verify(User user, string password) => _hasher.VerifyHashedPassword(user, user.PasswordHash, password) is not PasswordVerificationResult.Failed;
}

