IF DB_ID(N'SystemPOS') IS NULL
BEGIN
    CREATE DATABASE [SystemPOS];
END;
GO

USE [SystemPOS];
GO

IF OBJECT_ID(N'dbo.Companies', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Companies
    (
        Id INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Companies PRIMARY KEY,
        Name NVARCHAR(150) NOT NULL,
        TaxId NVARCHAR(50) NULL,
        IsActive BIT NOT NULL CONSTRAINT DF_Companies_IsActive DEFAULT (1),
        CreatedAtUtc DATETIME2(3) NOT NULL CONSTRAINT DF_Companies_CreatedAtUtc DEFAULT (SYSUTCDATETIME())
    );
END;
GO

IF OBJECT_ID(N'dbo.Branches', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Branches
    (
        Id INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Branches PRIMARY KEY,
        CompanyId INT NOT NULL,
        Name NVARCHAR(120) NOT NULL,
        Address NVARCHAR(300) NULL,
        IsActive BIT NOT NULL CONSTRAINT DF_Branches_IsActive DEFAULT (1),
        CONSTRAINT FK_Branches_Companies FOREIGN KEY (CompanyId) REFERENCES dbo.Companies(Id),
        CONSTRAINT UQ_Branches_Company_Name UNIQUE (CompanyId, Name)
    );
END;
GO

IF OBJECT_ID(N'dbo.Products', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Products
    (
        Id INT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Products PRIMARY KEY,
        CompanyId INT NOT NULL,
        Sku NVARCHAR(60) NOT NULL,
        Barcode NVARCHAR(80) NULL,
        Name NVARCHAR(180) NOT NULL,
        Cost DECIMAL(18,2) NOT NULL CONSTRAINT DF_Products_Cost DEFAULT (0),
        Price DECIMAL(18,2) NOT NULL,
        MinimumStock DECIMAL(18,4) NOT NULL CONSTRAINT DF_Products_MinimumStock DEFAULT (0),
        IsActive BIT NOT NULL CONSTRAINT DF_Products_IsActive DEFAULT (1),
        CreatedAtUtc DATETIME2(3) NOT NULL CONSTRAINT DF_Products_CreatedAtUtc DEFAULT (SYSUTCDATETIME()),
        CONSTRAINT FK_Products_Companies FOREIGN KEY (CompanyId) REFERENCES dbo.Companies(Id),
        CONSTRAINT UQ_Products_Company_Sku UNIQUE (CompanyId, Sku),
        CONSTRAINT CK_Products_Cost CHECK (Cost >= 0),
        CONSTRAINT CK_Products_Price CHECK (Price >= 0),
        CONSTRAINT CK_Products_MinimumStock CHECK (MinimumStock >= 0)
    );

    CREATE UNIQUE INDEX UX_Products_Company_Barcode
        ON dbo.Products(CompanyId, Barcode)
        WHERE Barcode IS NOT NULL;
END;
GO

IF OBJECT_ID(N'dbo.InventoryStocks', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.InventoryStocks
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_InventoryStocks PRIMARY KEY,
        CompanyId INT NOT NULL,
        BranchId INT NOT NULL,
        ProductId INT NOT NULL,
        Quantity DECIMAL(18,4) NOT NULL CONSTRAINT DF_InventoryStocks_Quantity DEFAULT (0),
        UpdatedAtUtc DATETIME2(3) NOT NULL CONSTRAINT DF_InventoryStocks_UpdatedAtUtc DEFAULT (SYSUTCDATETIME()),
        CONSTRAINT FK_InventoryStocks_Companies FOREIGN KEY (CompanyId) REFERENCES dbo.Companies(Id),
        CONSTRAINT FK_InventoryStocks_Branches FOREIGN KEY (BranchId) REFERENCES dbo.Branches(Id),
        CONSTRAINT FK_InventoryStocks_Products FOREIGN KEY (ProductId) REFERENCES dbo.Products(Id),
        CONSTRAINT UQ_InventoryStocks_Company_Branch_Product UNIQUE (CompanyId, BranchId, ProductId),
        CONSTRAINT CK_InventoryStocks_Quantity CHECK (Quantity >= 0)
    );
END;
GO

IF OBJECT_ID(N'dbo.InventoryMovements', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.InventoryMovements
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_InventoryMovements PRIMARY KEY,
        CompanyId INT NOT NULL,
        BranchId INT NOT NULL,
        ProductId INT NOT NULL,
        Type INT NOT NULL,
        Quantity DECIMAL(18,4) NOT NULL,
        QuantityBefore DECIMAL(18,4) NOT NULL,
        QuantityAfter DECIMAL(18,4) NOT NULL,
        Reference NVARCHAR(100) NULL,
        Notes NVARCHAR(500) NULL,
        CreatedAtUtc DATETIME2(3) NOT NULL CONSTRAINT DF_InventoryMovements_CreatedAtUtc DEFAULT (SYSUTCDATETIME()),
        CONSTRAINT FK_InventoryMovements_Companies FOREIGN KEY (CompanyId) REFERENCES dbo.Companies(Id),
        CONSTRAINT FK_InventoryMovements_Branches FOREIGN KEY (BranchId) REFERENCES dbo.Branches(Id),
        CONSTRAINT FK_InventoryMovements_Products FOREIGN KEY (ProductId) REFERENCES dbo.Products(Id),
        CONSTRAINT CK_InventoryMovements_Type CHECK (Type BETWEEN 1 AND 5),
        CONSTRAINT CK_InventoryMovements_QuantityAfter CHECK (QuantityAfter >= 0)
    );

    CREATE INDEX IX_InventoryMovements_Company_Branch_Product_Date
        ON dbo.InventoryMovements(CompanyId, BranchId, ProductId, CreatedAtUtc DESC);
END;
GO

IF OBJECT_ID(N'dbo.Sales', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.Sales
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_Sales PRIMARY KEY,
        CompanyId INT NOT NULL,
        BranchId INT NOT NULL,
        SaleNumber NVARCHAR(40) NOT NULL,
        Status INT NOT NULL CONSTRAINT DF_Sales_Status DEFAULT (1),
        Subtotal DECIMAL(18,2) NOT NULL,
        Tax DECIMAL(18,2) NOT NULL CONSTRAINT DF_Sales_Tax DEFAULT (0),
        Total DECIMAL(18,2) NOT NULL,
        CreatedAtUtc DATETIME2(3) NOT NULL CONSTRAINT DF_Sales_CreatedAtUtc DEFAULT (SYSUTCDATETIME()),
        CONSTRAINT FK_Sales_Companies FOREIGN KEY (CompanyId) REFERENCES dbo.Companies(Id),
        CONSTRAINT FK_Sales_Branches FOREIGN KEY (BranchId) REFERENCES dbo.Branches(Id),
        CONSTRAINT UQ_Sales_Company_SaleNumber UNIQUE (CompanyId, SaleNumber),
        CONSTRAINT CK_Sales_Status CHECK (Status IN (1,2)),
        CONSTRAINT CK_Sales_Amounts CHECK (Subtotal >= 0 AND Tax >= 0 AND Total >= 0)
    );

    CREATE INDEX IX_Sales_Company_Branch_Date
        ON dbo.Sales(CompanyId, BranchId, CreatedAtUtc DESC);
END;
GO

IF OBJECT_ID(N'dbo.SaleItems', N'U') IS NULL
BEGIN
    CREATE TABLE dbo.SaleItems
    (
        Id BIGINT IDENTITY(1,1) NOT NULL CONSTRAINT PK_SaleItems PRIMARY KEY,
        SaleId BIGINT NOT NULL,
        ProductId INT NOT NULL,
        ProductName NVARCHAR(180) NOT NULL,
        Quantity DECIMAL(18,4) NOT NULL,
        UnitPrice DECIMAL(18,2) NOT NULL,
        LineTotal DECIMAL(18,2) NOT NULL,
        CONSTRAINT FK_SaleItems_Sales FOREIGN KEY (SaleId) REFERENCES dbo.Sales(Id),
        CONSTRAINT FK_SaleItems_Products FOREIGN KEY (ProductId) REFERENCES dbo.Products(Id),
        CONSTRAINT CK_SaleItems_Quantity CHECK (Quantity > 0),
        CONSTRAINT CK_SaleItems_Amounts CHECK (UnitPrice >= 0 AND LineTotal >= 0)
    );

    CREATE INDEX IX_SaleItems_SaleId ON dbo.SaleItems(SaleId);
END;
GO

PRINT 'SystemPOS initial schema created successfully.';
GO
