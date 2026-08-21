using System.Data.Common;

namespace SDV.Infrastructure.Persistence;

public interface IDbConnectionFactory
{
    Task<DbConnection> OpenAsync(CancellationToken cancellationToken);
}
