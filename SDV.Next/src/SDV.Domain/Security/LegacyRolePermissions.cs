namespace SDV.Domain.Security;

public static class LegacyRolePermissions
{
    private static readonly IReadOnlyDictionary<int, string[]> Map = new Dictionary<int, string[]>
    {
        [2] = ["menu.view", "clients.view", "clients.create", "orders.view", "orders.create", "sales.view"],
        [4] = ["menu.view", "clients.view", "clients.create", "routes.view", "market.view"],
        [6] = ["menu.view", "reports.view", "reports.export", "users.view", "users.manage"],
        [15] = ["menu.view", "clients.view", "routes.view", "market.view", "inventory.view"],
        [116] = ["menu.view", "claims.view", "claims.manage", "inventory.view"],
        [155] = ["menu.view", "clients.view", "routes.view", "market.view", "reports.view"]
    };

    public static IReadOnlyCollection<string> ForRole(int roleId) => Map.TryGetValue(roleId, out var permissions) ? permissions : [];
    public static bool IsAllowed(int roleId) => Map.ContainsKey(roleId);
}
