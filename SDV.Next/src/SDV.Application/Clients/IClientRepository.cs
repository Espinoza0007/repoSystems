using SDV.Contracts.Clients;

namespace SDV.Application.Clients;

public interface IClientRepository
{
    Task<ClientPage> ListForUserAsync(int userId, string? search, int page, int pageSize, CancellationToken cancellationToken);
}
