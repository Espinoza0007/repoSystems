using SDV.Contracts.Authentication;

namespace SDV.Application.Authentication;

public sealed class AuthenticationService(IUserRepository users, IPasswordVerifier passwords, ITokenService tokens) : IAuthenticationService
{
    public async Task<LoginResponse?> LoginAsync(LoginRequest request, CancellationToken cancellationToken)
    {
        var user = await users.FindByUsernameAsync(request.Username.Trim(), cancellationToken);
        if (user is null || !user.IsActive || !passwords.Verify(user, request.Password)) return null;
        return tokens.Create(user);
    }
}

