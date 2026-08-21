using SDV.Application.Authentication;
using SDV.Contracts.Authentication;
using SDV.Domain.Users;

namespace SDV.UnitTests;

public sealed class AuthenticationServiceTests
{
    [Fact] public async Task Rejects_unknown_user()
    {
        var service = new AuthenticationService(new MissingUsers(), new Passwords(), new Tokens());
        Assert.Null(await service.LoginAsync(new LoginRequest("unknown", "secret"), default));
    }
    private sealed class MissingUsers : IUserRepository { public Task<User?> FindByUsernameAsync(string username, CancellationToken cancellationToken) => Task.FromResult<User?>(null); }
    private sealed class Passwords : IPasswordVerifier { public bool Verify(User user, string password) => true; }
    private sealed class Tokens : ITokenService { public LoginResponse Create(User user) => throw new NotImplementedException(); }
}

