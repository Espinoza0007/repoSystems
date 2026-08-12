# SystemPOS

SystemPOS es un sistema comercial para micro y pequeñas empresas construido como **una sola aplicación ASP.NET Core MVC**.

## Qué incluye actualmente

- Interfaz visual MVC al iniciar con F5 desde Visual Studio
- Login con ASP.NET Core Identity
- Roles `Administrator` y `Cashier`
- Administración de usuarios
- Activación y desactivación de usuarios
- Dashboard inicial
- Catálogo visual de productos
- Creación y edición de productos
- SQL Server mediante Entity Framework Core
- Base local de desarrollo creada automáticamente
- Diseño responsive para escritorio y móvil

## Abrir en Visual Studio

Abrir:

```text
SystemPOS.sln
```

La solución contiene un solo proyecto:

```text
SystemPOS.csproj
```

Al depurar con F5 se abrirá la aplicación web y, si no existe sesión, se mostrará el login.

## Acceso inicial de desarrollo

```text
Usuario: admin@systempos.local
Contraseña: Admin123!
```

Este usuario se crea únicamente cuando el ambiente es `Development`. Cambiar estas credenciales antes de cualquier publicación real.

## Base de datos local

Para facilitar el desarrollo desde Visual Studio en Windows se utiliza por defecto:

```text
(localdb)\MSSQLLocalDB
Database=SystemPOS
```

La primera ejecución crea automáticamente la base y las tablas de Identity y del sistema.

## Próximos módulos

1. Empresas y sucursales
2. Inventario y kardex
3. Apertura y cierre de caja
4. POS / carrito de venta
5. Venta y descuento de inventario
6. Ticket
7. Compras y proveedores
8. Reportes
