using Dapper;
using SDV.Application.Authentication;
using SDV.Domain.Users;
using SDV.Infrastructure.Persistence;

namespace SDV.Infrastructure.Authentication;

public sealed class UserRepository(ISqlConnectionFactory connections) : IUserRepository
{
    public async Task<User?> FindByUsernameAsync(string username, CancellationToken cancellationToken)
    {
        const string sql = """
            SELECT TOP (1)
                Usu_Id AS Id,
                Usu_usuario AS Username,
                Usu_nombre_usuario AS DisplayName,
                Usu_contrasena_hash AS PasswordHash,
                CAST(Usu_estado AS bit) AS IsActive
            FROM dbo.tbl_usuarios
            WHERE Usu_usuario = @Username;
            """;
        await using var connection = await connections.OpenAsync(cancellationToken);
        return await connection.QuerySingleOrDefaultAsync<User>(new CommandDefinition(sql, new { Username = username }, cancellationToken: cancellationToken));
    }
}

