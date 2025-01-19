-- Insert data into ProductCategory table
INSERT INTO ProductCategory (Name)
VALUES
    ('Electronics'), 
    ('Groceries'), 
    ('Beauty Products'), 
    ('Clothing'), 
    ('Sports Equipment'), 
    ('Furniture'), 
    ('Toys'), 
    ('Stationery'), 
    ('Appliances'), 
    ('Books');

-- Insert data into Unit table
INSERT INTO Unit (Name)
VALUES
    ('Piece'), 
    ('Kilogram'), 
    ('Litre'), 
    ('Pack'), 
    ('Box'), 
    ('Meter'), 
    ('Dozen'), 
    ('Gram'), 
    ('Set'), 
    ('Carton');

-- Insert data into Supplier table
INSERT INTO Supplier (Name, TaxID, PhoneNumber, Email)
VALUES
    ('TechWorld', 'TX12345', '+123456789', 'info@techworld.com'),
    ('FreshFarm', 'TX54321', '+987654321', 'contact@freshfarm.com'),
    ('BeautyPlus', 'TX67890', '+112233445', 'sales@beautyplus.com'),
    ('ClothHouse', 'TX98765', '+223344556', 'info@clothhouse.com'),
    ('SportsGear', 'TX54322', '+334455667', 'support@sportsgear.com'),
    ('FurniDecor', 'TX99887', '+445566778', 'sales@furnidecor.com'),
    ('ToyPlanet', 'TX66554', '+556677889', 'contact@toyplanet.com'),
    ('PaperCraft', 'TX11223', '+667788990', 'info@papercraft.com'),
    ('ApplianceWorld', 'TX33445', '+778899001', 'support@applianceworld.com'),
    ('BookBarn', 'TX55667', '+889900112', 'sales@bookbarn.com');

-- Insert data into Product table
INSERT INTO Product (Name, CategoryID, Price, ProducerID, UnitID, Description)
VALUES
    ('Smartphone', 1, 699.99, 1, 1, 'A high-end smartphone with advanced features'), -- UnitID 1: Piece
    ('Apple', 2, 0.99, 2, 2, 'Fresh organic apples'), -- UnitID 2: Kilogram
    ('Milk', 2, 1.50, 2, 3, 'Organic milk sourced from local farms'), -- UnitID 3: Litre
    ('Foundation Cream', 3, 25.00, 3, 1, 'Long-lasting foundation cream for makeup'), -- UnitID 1: Piece
    ('T-shirt', 4, 15.00, 4, 1, 'Comfortable cotton T-shirt available in all sizes'), -- UnitID 1: Piece
    ('Basketball', 5, 20.00, 5, 1, 'Professional basketball with high grip and durability'), -- UnitID 1: Piece
    ('Office Chair', 6, 120.00, 6, 1, 'Ergonomic chair with adjustable height and back support'), -- UnitID 1: Piece
    ('Toy Car', 7, 10.00, 7, 1, 'Battery-operated toy car for kids'), -- UnitID 1: Piece
    ('Notebook', 8, 2.00, 8, 1, '100-page ruled notebook for school and office use'), -- UnitID 1: Piece
    ('Microwave Oven', 9, 150.00, 9, 1, 'Compact and efficient microwave oven for quick meals'); -- UnitID 1: Piece
