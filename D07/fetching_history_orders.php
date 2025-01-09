

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'db_connection.php';

header('Content-Type: application/json');

try {
    // Отримання фільтрів із GET-запиту
    $employeeFilter = isset($_GET['employee']) ? $_GET['employee'] : '';
    $warehouseFilter = isset($_GET['warehouse']) ? $_GET['warehouse'] : '';
    $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';

    // Базовий SQL-запит
    $query = "
        SELECT 
            o.OrderID, 
            o.Name AS OrderName, 
            o.Price AS OrderPrice, 
            MIN(op.ShippingDate) AS OrderDate, -- Використовуємо мінімальну дату доставки
            CONCAT(e.FirstName, ' ', e.LastName) AS EmployeeName, 
            w.Name AS WarehouseName, 
            STRING_AGG(p.Name + ' (Qty: ' + CAST(op.Quantity AS NVARCHAR) + ')', ', ') AS Products
        FROM [Order] o
        JOIN Employee e ON o.EmployeeID = e.EmployeeID
        JOIN Warehouse w ON o.WarehouseID = w.WarehouseID
        JOIN Order_Product op ON o.OrderID = op.OrderID
        JOIN Product p ON op.ProductID = p.ProductID
        WHERE 1=1
    ";

    // Динамічне додавання фільтрів
    $params = [];

    // Фільтр за працівником
    if (!empty($employeeFilter)) {
        $query .= " AND CONCAT(e.FirstName, ' ', e.LastName) LIKE :employeeFilter";
        $params[':employeeFilter'] = '%' . $employeeFilter . '%';
    }

    // Фільтр за складом
    if (!empty($warehouseFilter)) {
        $query .= " AND w.Name LIKE :warehouseFilter";
        $params[':warehouseFilter'] = '%' . $warehouseFilter . '%';
    }

    // Фільтри за датами (обидва або окремо)
    if (!empty($dateFrom) && !empty($dateTo)) {
        $query .= " AND op.ShippingDate BETWEEN :dateFrom AND :dateTo";
        $params[':dateFrom'] = $dateFrom;
        $params[':dateTo'] = $dateTo;
    } elseif (!empty($dateFrom)) {
        $query .= " AND op.ShippingDate >= :dateFrom";
        $params[':dateFrom'] = $dateFrom;
    } elseif (!empty($dateTo)) {
        $query .= " AND op.ShippingDate <= :dateTo";
        $params[':dateTo'] = $dateTo;
    }

    // Групування та сортування
    $query .= "
        GROUP BY o.OrderID, o.Name, o.Price, e.FirstName, e.LastName, w.Name
        ORDER BY MIN(op.ShippingDate) DESC
    ";

    // Виконання запиту
    $stmt = $conn->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Повертаємо дані у форматі JSON
    echo json_encode($orders);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>

