using System.Data.Common;
using Microsoft.Data.SqlClient;
namespace SDV.Infrastructure.Persistence;
public sealed class SqlConnectionFactory(string connectionString) : ISqlConnectionFactory
{
    public async Task<DbConnection> OpenAsync(CancellationToken cancellationToken)
    {
        var connection = new SqlConnection(connectionString);
        await connection.OpenAsync(cancellationToken);
        return connection;
    }
}

