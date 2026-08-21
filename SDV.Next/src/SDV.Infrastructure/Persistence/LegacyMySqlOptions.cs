using Microsoft.Extensions.Configuration;
using MySqlConnector;

namespace SDV.Infrastructure.Persistence;

public static class LegacyMySqlOptions
{
    public static string ResolveConnectionString(IConfiguration configuration)
    {
        var configured = configuration.GetConnectionString("Sdv");
        if (!string.IsNullOrWhiteSpace(configured)) return configured;

        var host = configuration["MYSQL_HOST"];
        var user = configuration["MYSQL_USER"];
        var password = configuration["MYSQL_PASSWORD"];
        var database = configuration["MYSQL_DATABASE"];
        if (new[] { host, user, password, database }.Any(string.IsNullOrWhiteSpace))
            throw new InvalidOperationException("Configure ConnectionStrings:Sdv or MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD and MYSQL_DATABASE.");

        return new MySqlConnectionStringBuilder
        {
            Server = host,
            UserID = user,
            Password = password,
            Database = database,
            Port = uint.TryParse(configuration["MYSQL_PORT"], out var port) ? port : 3306,
            SslMode = MySqlSslMode.Preferred,
            Pooling = true,
            MinimumPoolSize = 0,
            MaximumPoolSize = 100,
            ConnectionTimeout = 15,
            DefaultCommandTimeout = 30,
            CharacterSet = "utf8mb4"
        }.ConnectionString;
    }
}
