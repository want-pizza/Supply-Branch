-- Create the database
CREATE DATABASE StoreManagement;

-- Use the database
USE StoreManagement;

-- Create the Product table
CREATE TABLE Product (
    ProductID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    ProducerID INT NOT NULL,
    UnitID INT NOT NULL
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

-- Create the Supplier table
CREATE TABLE Supplier (
    SupplierID INT IDENTITY(1,1) PRIMARY KEY,
    Name NVARCHAR(255) NOT NULL,
    TaxID NVARCHAR(50) NOT NULL,
    PhoneNumber NVARCHAR(20) NOT NULL,
    Email NVARCHAR(255) NOT NULL
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
ALTER TABLE Product
ADD CONSTRAINT FK_Product_Producer FOREIGN KEY (ProducerID) REFERENCES Supplier(SupplierID);
GO

ALTER TABLE Product
DROP CONSTRAINT FK_Product_Unit;
GO

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