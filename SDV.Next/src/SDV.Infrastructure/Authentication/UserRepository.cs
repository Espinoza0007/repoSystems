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
                u.Usu_Id AS Id,
                u.Usu_usuario AS Username,
                u.Usu_nombre_usuario AS DisplayName,
                u.Usu_contrasena AS PasswordHash,
                CAST(u.Usu_estado AS bit) AS IsActive,
                p.Priv_Id AS RoleId,
                p.Priv_descripcion AS RoleName,
                r.Ru_Id AS RouteId,
                r.Ru_nombre AS RouteName,
                c.Ca_Id AS ChannelId,
                c.Ca_nombre AS ChannelName,
                dis.Dis_Id AS DistributorId,
                dis.Dis_nombre AS DistributorName,
                di.Di_Id AS DivisionId,
                pa.P_Id AS CountryId,
                pa.P_nombre AS CountryName,
                CAST(u.Usu_act_contrasena AS bit) AS MustChangePassword
            FROM dbo.tbl_usuario AS u
            INNER JOIN dbo.tbl_privilegio AS p ON u.Usu_Priv_Id = p.Priv_Id
            INNER JOIN dbo.tbl_rutas AS r ON u.Usu_Ru_Id = r.Ru_Id
            INNER JOIN dbo.tbl_distrito AS d ON r.Ru_Dist_Id = d.Dist_Id
            INNER JOIN dbo.tbl_canal AS c ON d.Dist_Ca_Id = c.Ca_Id
            INNER JOIN dbo.tbl_distribuidora AS dis ON c.Ca_Dis_Id = dis.Dis_Id
            INNER JOIN dbo.tbl_division AS di ON dis.Dis_Di_Id = di.Di_Id
            INNER JOIN dbo.tbl_pais AS pa ON di.Di_P_Id = pa.P_Id
            WHERE u.Usu_usuario = @Username
              AND u.Usu_estado = 1
              AND u.Usu_Priv_Id IN (2, 4, 6, 15, 116, 155);
            """;
        await using var connection = await connections.OpenAsync(cancellationToken);
        return await connection.QuerySingleOrDefaultAsync<User>(new CommandDefinition(sql, new { Username = username }, cancellationToken: cancellationToken));
    }
}
