
-- Create the ProductCategory table
CREATE TABLE ProductCategory (
    CategoryID INT IDENTITY(1,1) PRIMARY KEY, -- Унікальний ідентифікатор категорії
    Name NVARCHAR(255) NOT NULL UNIQUE       -- Назва категорії (наприклад, "Електроніка"), має бути унікальною
);

-- Create the Unit table
CREATE TABLE Unit (
    UnitID INT IDENTITY(1,1) PRIMARY KEY,     -- Унікальний ідентифікатор одиниці
    Name NVARCHAR(50) NOT NULL UNIQUE         -- Назва одиниці виміру (наприклад, "кг", "шт."), має бути унікальною
);

-- Create the Supplier table
CREATE TABLE Supplier (
    SupplierID INT IDENTITY(1,1) PRIMARY KEY, -- Унікальний ідентифікатор постачальника
    Name NVARCHAR(255) NOT NULL UNIQUE,       -- Назва постачальника, має бути унікальною
    TaxID NVARCHAR(50) NOT NULL UNIQUE,       -- Ідентифікаційний номер платника податків, має бути унікальним
    PhoneNumber NVARCHAR(20) NOT NULL UNIQUE, -- Номер телефону, має бути унікальним
    Email NVARCHAR(255) NOT NULL UNIQUE       -- Email постачальника, має бути унікальним
);

-- Create the Product table
CREATE TABLE Product (
    ProductID INT IDENTITY(1,1) PRIMARY KEY,  -- Унікальний ідентифікатор продукту
    Name NVARCHAR(255) NOT NULL,              -- Назва продукту
    CategoryID INT NOT NULL,                  -- Посилання на категорію продукту
    Price DECIMAL(10, 2) NOT NULL,            -- Ціна продукту
    ProducerID INT NOT NULL,                  -- Посилання на постачальника
    UnitID INT NOT NULL,                      -- Посилання на одиницю виміру
    Description NVARCHAR(MAX) NULL,                -- Опис продукту
    CONSTRAINT FK_Product_Category FOREIGN KEY (CategoryID) REFERENCES ProductCategory(CategoryID),
    CONSTRAINT FK_Product_Producer FOREIGN KEY (ProducerID) REFERENCES Supplier(SupplierID),
    CONSTRAINT FK_Product_Unit FOREIGN KEY (UnitID) REFERENCES Unit(UnitID),
    CONSTRAINT UQ_Product UNIQUE (Name, ProducerID) -- Назва продукту в межах одного постачальника має бути унікальною
);


-- Create the Warehouse table
CREATE TABLE Warehouse (
    WarehouseID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL,
    Location NVARCHAR(255) NOT NULL
);

-- Create the Employee table
CREATE TABLE Employee (
    EmployeeID INT IDENTITY(1,1) PRIMARY KEY,
    FirstName NVARCHAR(255) NOT NULL,
    LastName NVARCHAR(255) NOT NULL,
    Position NVARCHAR(255) NOT NULL,
    PhoneNumber NVARCHAR(20) NOT NULL,
    Email NVARCHAR(255) NOT NULL
);

-- Create the OrderStatus table
CREATE TABLE OrderStatus (
    OrderStatusID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL
);



-- Create the Order table
CREATE TABLE [Order] (
    OrderID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    EmployeeID INT NOT NULL,
    WarehouseID INT NOT NULL,
    OrderStatusID INT NOT NULL,
    CONSTRAINT FK_Order_Employee FOREIGN KEY (EmployeeID) REFERENCES Employee(EmployeeID),
    CONSTRAINT FK_Order_Warehouse FOREIGN KEY (WarehouseID) REFERENCES Warehouse(WarehouseID),
    CONSTRAINT FK_Order_OrderStatus FOREIGN KEY (OrderStatusID) REFERENCES OrderStatus(OrderStatusID)
);

-- Create the Order_Product table
CREATE TABLE Order_Product (
    OrderProductID INT IDENTITY(1,1) PRIMARY KEY,
    OrderID INT NOT NULL,
    ProductID INT NOT NULL,
    SupplierID INT NOT NULL,
    Quantity INT NOT NULL,
    ShippingDate DATE NOT NULL,
    DeliveryDate DATE NOT NULL,
    CONSTRAINT FK_OrderProduct_Order FOREIGN KEY (OrderID) REFERENCES [Order](OrderID),
    CONSTRAINT FK_OrderProduct_Product FOREIGN KEY (ProductID) REFERENCES Product(ProductID),
    CONSTRAINT FK_OrderProduct_Supplier FOREIGN KEY (SupplierID) REFERENCES Supplier(SupplierID)
);

-- Create the Warehouse_Product table
CREATE TABLE Warehouse_Product (
    WarehouseProductID INT IDENTITY(1,1) PRIMARY KEY,
    Quantity INT NOT NULL,
    ProductID INT NOT NULL,
    MinQuantity INT NOT NULL,
    MaxQuantity INT NOT NULL,
	WarehouseID INT NOT NULL,
    CONSTRAINT FK_WarehouseProduct_Product FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

-- Add foreign key constraints to Product table


ALTER TABLE Warehouse
ADD CONSTRAINT FK_Warehouse_Product FOREIGN KEY (WarehouseID) REFERENCES Warehouse_Product(WarehouseProductID);
GO

-- Create the User table
CREATE TABLE [User] (
    UserID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL,
    Role NVARCHAR(50) NOT NULL -- Example roles: 'Admin', 'Manager', 'Employee'
);

-- Create the OrderHistory table
CREATE TABLE OrderHistory (
    HistoryID INT IDENTITY(1,1) PRIMARY KEY,
    OrderID INT NOT NULL,
    StatusID INT NOT NULL, -- The previous status of the order
    ChangeDate DATETIME NOT NULL DEFAULT GETDATE(),
    ChangedBy INT NOT NULL, -- The User who changed the status
);