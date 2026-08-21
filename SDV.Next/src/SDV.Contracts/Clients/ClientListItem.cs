namespace SDV.Contracts.Clients;

public sealed record ClientListItem(
    int Id,
    string Code,
    string Name,
    string Address,
    string? Latitude,
    string? Longitude,
    string Route);

public sealed record ClientPage(IReadOnlyCollection<ClientListItem> Items, int Total, int Page, int PageSize);
