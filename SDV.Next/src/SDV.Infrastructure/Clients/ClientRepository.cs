using Dapper;
using SDV.Application.Clients;
using SDV.Contracts.Clients;
using SDV.Infrastructure.Persistence;

namespace SDV.Infrastructure.Clients;

public sealed class ClientRepository(IDbConnectionFactory connections) : IClientRepository
{
    public async Task<ClientPage> ListForUserAsync(int userId, string? search, int page, int pageSize, CancellationToken cancellationToken)
    {
        const string where = """
            FROM tbl_cliente c
            INNER JOIN tbl_rutas r ON r.Ru_Id = c.Cli_Ru_Id
            INNER JOIN tbl_usuario u ON u.Usu_Ru_Id = c.Cli_Ru_Id
            WHERE u.Usu_Id = @UserId
              AND c.Cli_estado_descarga = 1
              AND c.Cli_codigo NOT IN ('0', '0000000')
              AND (@Search = '' OR c.Cli_codigo LIKE @Pattern OR c.Cli_nombre LIKE @Pattern)
            """;
        var offset = (page - 1) * pageSize;
        var parameters = new { UserId = userId, Search = search?.Trim() ?? "", Pattern = $"%{search?.Trim()}%", Offset = offset, PageSize = pageSize };
        var command = new CommandDefinition($"""
            SELECT c.Cli_Id AS Id, c.Cli_codigo AS Code, c.Cli_nombre AS Name,
                   COALESCE(c.Cli_direccion, '') AS Address,
                   c.Cli_latitud AS Latitude, c.Cli_longitud AS Longitude,
                   r.Ru_nombre AS Route
            {where}
            ORDER BY c.Cli_nombre
            LIMIT @PageSize OFFSET @Offset;
            SELECT COUNT(*) {where};
            """, parameters, cancellationToken: cancellationToken);

        await using var connection = await connections.OpenAsync(cancellationToken);
        using var result = await connection.QueryMultipleAsync(command);
        var items = (await result.ReadAsync<ClientListItem>()).AsList();
        var total = await result.ReadSingleAsync<int>();
        return new ClientPage(items, total, page, pageSize);
    }
}
