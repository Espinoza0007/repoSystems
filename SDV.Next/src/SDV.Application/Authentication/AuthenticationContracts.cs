using SDV.Contracts.Authentication;
using SDV.Domain.Users;

namespace SDV.Application.Authentication;

public interface IUserRepository
{
    Task<User?> FindByUsernameAsync(string username, CancellationToken cancellationToken);
}

public interface IPasswordVerifier
{
    bool Verify(User user, string password);
}

public interface ITokenService
{
    LoginResponse Create(User user);
}

public interface IAuthenticationService
{
    Task<LoginResponse?> LoginAsync(LoginRequest request, CancellationToken cancellationToken);
}

