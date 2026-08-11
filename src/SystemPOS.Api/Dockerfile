FROM mcr.microsoft.com/dotnet/sdk:10.0 AS build
WORKDIR /src

COPY ["src/SystemPOS.Domain/SystemPOS.Domain.csproj", "src/SystemPOS.Domain/"]
COPY ["src/SystemPOS.Application/SystemPOS.Application.csproj", "src/SystemPOS.Application/"]
COPY ["src/SystemPOS.Infrastructure/SystemPOS.Infrastructure.csproj", "src/SystemPOS.Infrastructure/"]
COPY ["src/SystemPOS.Api/SystemPOS.Api.csproj", "src/SystemPOS.Api/"]

RUN dotnet restore "src/SystemPOS.Api/SystemPOS.Api.csproj"

COPY . .
RUN dotnet publish "src/SystemPOS.Api/SystemPOS.Api.csproj" -c Release -o /app/publish /p:UseAppHost=false

FROM mcr.microsoft.com/dotnet/aspnet:10.0 AS final
WORKDIR /app
EXPOSE 8080
ENV ASPNETCORE_URLS=http://+:8080
COPY --from=build /app/publish .
ENTRYPOINT ["dotnet", "SystemPOS.Api.dll"]
