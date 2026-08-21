using Microsoft.Extensions.Diagnostics.HealthChecks;
using Microsoft.Extensions.Logging;
using MySqlConnector;
using SDV.Infrastructure.Persistence;

namespace SDV.Infrastructure.Health;

public sealed class DatabaseHealthCheck(IDbConnectionFactory connections, ILogger<DatabaseHealthCheck> logger) : IHealthCheck
{
    public async Task<HealthCheckResult> CheckHealthAsync(HealthCheckContext context, CancellationToken cancellationToken = default)
    {
        try
        {
            await using var connection = await connections.OpenAsync(cancellationToken);
            await using var command = connection.CreateCommand();
            command.CommandText = "SELECT 1";
            await command.ExecuteScalarAsync(cancellationToken);
            return HealthCheckResult.Healthy("MySQL disponible");
        }
        catch (MySqlException exception)
        {
            logger.LogError(exception, "Falló la conexión MySQL. Código: {ErrorCode}, número: {Number}", exception.ErrorCode, exception.Number);
            return HealthCheckResult.Unhealthy("No fue posible conectar con MySQL", exception);
        }
        catch (Exception exception)
        {
            logger.LogError(exception, "Falló inesperadamente la comprobación de MySQL");
            return HealthCheckResult.Unhealthy("Error inesperado al comprobar MySQL", exception);
        }
    }
}
