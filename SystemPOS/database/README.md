# Base de datos de SystemPOS

La aplicación MVC usa SQL Server mediante Entity Framework Core e Identity.

En desarrollo, `appsettings.json` apunta a SQL Server LocalDB y la base `SystemPOS` se crea automáticamente en el primer arranque con las tablas de Identity y las entidades comerciales actuales.

Los scripts versionados y migraciones SQL del producto se irán dejando en esta carpeta a medida que agreguemos inventario, caja, ventas, compras y reportes.
