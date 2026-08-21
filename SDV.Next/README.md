# SDV Next

Modernización progresiva de SDV hacia .NET con una API ASP.NET Core, acceso a datos mediante Dapper/ADO.NET, portal HTML/CSS/JavaScript y cliente .NET MAUI HybridWebView.

## Requisitos

- Visual Studio 2022 con workloads ASP.NET y .NET MAUI
- SDK .NET 10
- Docker Desktop
- SQL Server accesible desde la API

## Ejecución

```powershell
dotnet restore SDV.Next.sln
dotnet build SDV.Next.sln
docker compose -f deploy/docker-compose.yml up --build
```

La API queda en `http://localhost:8080`, el portal en `http://localhost:8081` y Swagger en `http://localhost:8080/swagger` durante desarrollo.

## Seguridad

Las credenciales nunca se guardan en Git. Configure `ConnectionStrings__Sdv`, `Jwt__Key`, `Jwt__Issuer` y `Jwt__Audience` mediante variables de entorno o secretos de desarrollo.
