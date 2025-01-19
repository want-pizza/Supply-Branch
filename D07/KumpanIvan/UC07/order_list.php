<?php
include 'db_connect.php'; 

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

try {
    
    if (!$polaczenie) {
        throw new Exception('Nie udało się połączyć z bazą danych.');
    }

    
    $query = "
        SELECT 
            o.OrderID, 
            o.Name AS OrderName, 
            o.Price, 
            CONCAT(e.FirstName, ' ', e.LastName) AS EmployeeName, -- Łączenie imienia i nazwiska pracownika
            w.Name AS WarehouseName, -- Nazwa magazynu
            s.Name AS StatusName -- Nazwa statusu
        FROM [Order] o
        JOIN Employee e ON o.EmployeeID = e.EmployeeID
        JOIN Warehouse w ON o.WarehouseID = w.WarehouseID
        JOIN OrderStatus s ON o.OrderStatusID = s.OrderStatusID
        ORDER BY o.OrderID
        OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY
    ";

    $stmt = $polaczenie->prepare($query);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $totalOrdersQuery = "SELECT COUNT(*) FROM [Order]";
    $totalOrders = $polaczenie->query($totalOrdersQuery)->fetchColumn();
    $totalPages = ceil($totalOrders / $limit);

} catch (Exception $e) {
    $errorMessage = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista zamówień</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Lista zamówień</h1>
        <nav>
            <ul>
                <li><a href="order_list.php">Lista zamówień</a></li>
                <li><a href="add_order.php">Dodaj zamówienie</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h2>Wszystkie zamówienia</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Cena</th>
                    <th>Pracownik</th>
                    <th>Magazyn</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['OrderID']) ?></td>
                            <td><?= htmlspecialchars($order['OrderName']) ?></td>
                            <td><?= htmlspecialchars($order['Price']) ?> zł</td>
                            <td><?= htmlspecialchars($order['EmployeeName']) ?></td>
                            <td><?= htmlspecialchars($order['WarehouseName']) ?></td>
                            <td><?= htmlspecialchars($order['StatusName']) ?></td>
                            <td><a href="order_details.php?order_id=<?= $order['OrderID'] ?>">Szczegóły</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Nie znaleziono zamówień.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (isset($totalPages) && $totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($errorMessage)): ?>
            <p class="error">Błąd: <?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
    </main>
    <footer>
        <p>© 2025 System zarządzania zamówieniami. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
