/* MySQL/RDS. Ejecutar primero en un ambiente de prueba. */
DROP PROCEDURE IF EXISTS sdv_security_migrate;
DELIMITER //
CREATE PROCEDURE sdv_security_migrate()
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_usuario' AND COLUMN_NAME = 'Usu_contrasena_v2'
    ) THEN
        ALTER TABLE tbl_usuario ADD COLUMN Usu_contrasena_v2 VARCHAR(512) NULL;
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_usuario' AND COLUMN_NAME = 'Usu_ultimo_acceso'
    ) THEN
        ALTER TABLE tbl_usuario ADD COLUMN Usu_ultimo_acceso DATETIME NULL;
    END IF;
END//
DELIMITER ;
CALL sdv_security_migrate();
DROP PROCEDURE sdv_security_migrate;

CREATE TABLE IF NOT EXISTS SDV_RefreshToken
(
    Id BIGINT NOT NULL AUTO_INCREMENT,
    UsuarioId INT NOT NULL,
    TokenHash BINARY(64) NOT NULL,
    CreadoEn DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ExpiraEn DATETIME NOT NULL,
    RevocadoEn DATETIME NULL,
    ReemplazadoPorId BIGINT NULL,
    Dispositivo VARCHAR(200) NULL,
    DireccionIp VARCHAR(45) NULL,
    PRIMARY KEY (Id),
    UNIQUE KEY UX_SDV_RefreshToken_TokenHash (TokenHash),
    KEY IX_SDV_RefreshToken_UsuarioId_ExpiraEn (UsuarioId, ExpiraEn),
    CONSTRAINT FK_SDV_RefreshToken_Usuario FOREIGN KEY (UsuarioId) REFERENCES tbl_usuario (Usu_Id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS SDV_AuditoriaSeguridad
(
    Id BIGINT NOT NULL AUTO_INCREMENT,
    UsuarioId INT NULL,
    Evento VARCHAR(80) NOT NULL,
    Exitoso TINYINT(1) NOT NULL,
    Fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    DireccionIp VARCHAR(45) NULL,
    UserAgent VARCHAR(500) NULL,
    CorrelationId VARCHAR(100) NULL,
    PRIMARY KEY (Id),
    KEY IX_SDV_AuditoriaSeguridad_UsuarioId_Fecha (UsuarioId, Fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
