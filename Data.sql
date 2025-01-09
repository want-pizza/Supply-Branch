-- Заповнення таблиці Employee
INSERT INTO [dbo].[Employee] (FirstName, LastName, Position, PhoneNumber, Email)
VALUES 
('John', 'Doe', 'Manager', '123-456-7890', 'johndoe@example.com'),
('Jane', 'Smith', 'Warehouse Worker', '987-654-3210', 'janesmith@example.com'),
('Alice', 'Johnson', 'Sales Manager', '555-123-4567', 'alice.johnson@example.com'),
('Bob', 'Williams', 'Assistant Manager', '555-234-5678', 'bob.williams@example.com');

-- Заповнення таблиці OrderStatus
INSERT INTO [dbo].[OrderStatus] (Name)
VALUES 
('Pending'),
('Shipped'),
('Delivered'),
('Canceled');

-- Заповнення таблиці Warehouse
INSERT INTO [dbo].[Warehouse] (Name, Location)
VALUES 
('Warehouse A', 'Location A'),
('Warehouse B', 'Location B');

-- Заповнення таблиці Supplier
INSERT INTO [dbo].[Supplier] (Name, TaxID, PhoneNumber, Email)
VALUES 
('Supplier One', '1234567890', '555-555-5555', 'supplier1@example.com'),
('Supplier Two', '0987654321', '555-555-1234', 'supplier2@example.com');

-- Заповнення таблиці Product
INSERT INTO [dbo].[Product] (Name, Price, ProducerID, UnitID)
VALUES 
('Product 1', 25.50, 1, 1),
('Product 2', 40.75, 2, 2),
('Product 3', 10.99, 1, 1);

-- Заповнення таблиці Warehouse_Product
INSERT INTO [dbo].[Warehouse_Product] (Quantity, ProductID, MinQuantity, MaxQuantity, WarehouseID)
VALUES 
(100, 1, 10, 200, 1),
(50, 2, 5, 100, 2),
(200, 3, 15, 300, 1);

-- Заповнення таблиці Order
INSERT INTO [dbo].[Order] (Name, Price, EmployeeID, WarehouseID, OrderStatusID)
VALUES 
('Order 1', 100.00, 1, 1, 1),
('Order 2', 200.50, 2, 2, 2),
('Order 3', 75.30, 3, 1, 3);

-- Заповнення таблиці Order_Product
INSERT INTO [dbo].[Order_Product] (OrderID, ProductID, SupplierID, Quantity, ShippingDate, DeliveryDate)
VALUES 
(1, 1, 1, 10, '2025-01-10', '2025-01-12'),
(2, 2, 2, 20, '2025-01-11', '2025-01-15'),
(3, 3, 1, 15, '2025-01-12', '2025-01-14');

-- Заповнення таблиці OrderHistory
INSERT INTO [dbo].[OrderHistory] (OrderID, StatusID, ChangeDate, ChangedBy)
VALUES 
(1, 1, '2025-01-09 12:00:00', 1),
(2, 2, '2025-01-09 12:30:00', 2),
(3, 3, '2025-01-09 13:00:00', 3);

-- Вставка тестових користувачів з хешованими паролями
INSERT INTO [dbo].[User] ([Login], [Password], [Role])
VALUES
('admin', HASHBYTES('SHA2_512', '123'), 'Administrator'),
('manager', HASHBYTES('SHA2_512', '123'), 'Manager'),
('employee', HASHBYTES('SHA2_512', '123'), 'Employee');
