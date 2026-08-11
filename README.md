# SystemPOS

SystemPOS is the first commercial product in `repoSystems`: a modular point-of-sale and inventory platform for micro and small businesses, starting with minimarkets.

## Product direction

The MVP focuses on a workflow that can be demonstrated and sold quickly:

1. Company and branch setup
2. Product catalog
3. Inventory by branch
4. Sales / POS transactions
5. Automatic inventory movements
6. Daily sales reporting

The architecture is multi-company from the beginning so future vertical modules (food service, barber shops, services, billing) can reuse the same core.

## Technology

- ASP.NET Core / .NET 10
- Entity Framework Core
- SQL Server
- REST API
- Docker

## Repository structure

```text
src/
  SystemPOS.Domain/
  SystemPOS.Application/
  SystemPOS.Infrastructure/
  SystemPOS.Api/
database/
  001_initial_schema.sql
docker-compose.yml
```

## MVP status

- [x] Initial repository structure
- [x] Core domain model
- [x] SQL Server initial schema
- [ ] Product CRUD
- [ ] Inventory operations
- [ ] POS sale transaction
- [ ] Authentication and roles
- [ ] Angular frontend
- [ ] Docker end-to-end validation

## Local database

The repository includes `database/001_initial_schema.sql` and a Docker Compose definition for SQL Server.

> Do not use the example development password in production. Configure secrets through environment variables or a secret manager.

## Commercial objective

Build one reusable platform and activate business-specific modules instead of maintaining separate products for each type of microenterprise.
