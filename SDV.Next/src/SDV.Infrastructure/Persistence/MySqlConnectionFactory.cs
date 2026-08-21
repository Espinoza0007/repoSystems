using System.Data.Common;
using MySqlConnector;

namespace SDV.Infrastructure.Persistence;

public sealed class MySqlConnectionFactory(string connectionString) : IDbConnectionFactory
{
    public async Task<DbConnection> OpenAsync(CancellationToken cancellationToken)
    {
        var connection = new MySqlConnection(connectionString);
        await connection.OpenAsync(cancellationToken);
        return connection;
    }
}
