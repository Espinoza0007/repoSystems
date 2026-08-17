/* Ejecutar primero en un ambiente de prueba. No modifica las contraseñas heredadas. */
IF COL_LENGTH('dbo.tbl_usuario', 'Usu_contrasena_v2') IS NULL
    ALTER TABLE dbo.tbl_usuario ADD Usu_contrasena_v2 NVARCHAR(512) NULL;
GO

IF COL_LENGTH('dbo.tbl_usuario', 'Usu_ultimo_acceso') IS NULL
    ALTER TABLE dbo.tbl_usuario ADD Usu_ultimo_acceso DATETIME2 NULL;
GO

IF OBJECT_ID('dbo.SDV_RefreshToken', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.SDV_RefreshToken
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_SDV_RefreshToken PRIMARY KEY,
        UsuarioId INT NOT NULL,
        TokenHash VARBINARY(64) NOT NULL,
        CreadoEn DATETIME2 NOT NULL CONSTRAINT DF_SDV_RefreshToken_CreadoEn DEFAULT SYSUTCDATETIME(),
        ExpiraEn DATETIME2 NOT NULL,
        RevocadoEn DATETIME2 NULL,
        ReemplazadoPorId BIGINT NULL,
        Dispositivo NVARCHAR(200) NULL,
        DireccionIp VARCHAR(45) NULL,
        CONSTRAINT FK_SDV_RefreshToken_Usuario FOREIGN KEY (UsuarioId) REFERENCES dbo.tbl_usuario(Usu_Id)
    );
    CREATE UNIQUE INDEX UX_SDV_RefreshToken_TokenHash ON dbo.SDV_RefreshToken(TokenHash);
    CREATE INDEX IX_SDV_RefreshToken_UsuarioId_ExpiraEn ON dbo.SDV_RefreshToken(UsuarioId, ExpiraEn);
END;
GO

IF OBJECT_ID('dbo.SDV_AuditoriaSeguridad', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.SDV_AuditoriaSeguridad
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_SDV_AuditoriaSeguridad PRIMARY KEY,
        UsuarioId INT NULL,
        Evento VARCHAR(80) NOT NULL,
        Exitoso BIT NOT NULL,
        Fecha DATETIME2 NOT NULL CONSTRAINT DF_SDV_AuditoriaSeguridad_Fecha DEFAULT SYSUTCDATETIME(),
        DireccionIp VARCHAR(45) NULL,
        UserAgent NVARCHAR(500) NULL,
        CorrelationId VARCHAR(100) NULL
    );
    CREATE INDEX IX_SDV_AuditoriaSeguridad_UsuarioId_Fecha ON dbo.SDV_AuditoriaSeguridad(UsuarioId, Fecha DESC);
END;
GO
