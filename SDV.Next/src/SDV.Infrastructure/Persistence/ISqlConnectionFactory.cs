using System.Data.Common;
namespace SDV.Infrastructure.Persistence;
public interface ISqlConnectionFactory { Task<DbConnection> OpenAsync(CancellationToken cancellationToken); }

