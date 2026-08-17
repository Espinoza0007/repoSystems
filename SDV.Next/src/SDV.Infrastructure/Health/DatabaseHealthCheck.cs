using Microsoft.Extensions.Diagnostics.HealthChecks;
using SDV.Infrastructure.Persistence;

namespace SDV.Infrastructure.Health;

public sealed class DatabaseHealthCheck(IDbConnectionFactory connections) : IHealthCheck
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
        catch (Exception exception)
        {
            return HealthCheckResult.Unhealthy("No fue posible conectar con MySQL", exception);
        }
    }
}
