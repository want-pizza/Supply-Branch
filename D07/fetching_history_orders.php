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
    O.OrderID,
    O.Name AS OrderName,
    O.Price,
    OH.ChangeDate AS OrderDate,
    OS.Name AS OrderStatus,
    CONCAT(E.FirstName, ' ', E.LastName) AS EmployeeName,
    W.Name AS WarehouseName
FROM
    dbo.[Order] O
JOIN
    dbo.OrderHistory OH ON O.OrderID = OH.OrderID
JOIN
    dbo.OrderStatus OS ON OH.StatusID = OS.OrderStatusID
JOIN
    dbo.Employee E ON O.EmployeeID = E.EmployeeID
JOIN
    dbo.Warehouse W ON O.WarehouseID = W.WarehouseID
WHERE 1=1
";

    // Динамічне додавання фільтрів
    $params = [];

    // Фільтр за працівником
    if (!empty($employeeFilter)) {
        $query .= " AND CONCAT(E.FirstName, ' ', E.LastName) LIKE :employeeFilter";
        $params[':employeeFilter'] = '%' . $employeeFilter . '%';
    }

    // Фільтр за складом
    if (!empty($warehouseFilter)) {
        $query .= " AND W.Name LIKE :warehouseFilter";
        $params[':warehouseFilter'] = '%' . $warehouseFilter . '%';
    }

    // Фільтри за датами (обидва або окремо)
    if (!empty($dateFrom) && !empty($dateTo)) {
        $query .= " AND OH.ChangeDate BETWEEN :dateFrom AND :dateTo";
        $params[':dateFrom'] = $dateFrom;
        $params[':dateTo'] = $dateTo;
    } elseif (!empty($dateFrom)) {
        $query .= " AND OH.ChangeDate >= :dateFrom";
        $params[':dateFrom'] = $dateFrom;
    } elseif (!empty($dateTo)) {
        $query .= " AND OH.ChangeDate <= :dateTo";
        $params[':dateTo'] = $dateTo;
    }

    // Сортування за датою зміни
    $query .= " ORDER BY OH.ChangeDate DESC";

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
