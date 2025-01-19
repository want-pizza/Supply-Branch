<?php
include 'db_connect.php';

$orderID = $_GET['order_id'] ?? null;
if (!$orderID) {
    die("Nie podano ID zamówienia.");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły zamówienia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Szczegóły zamówienia</h1>
        <nav>
            <ul>
                <li><a href="order_list.php">Lista zamówień</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php
        try {
            $stmt = $polaczenie->prepare("
    SELECT 
        o.OrderID, 
        o.Name AS OrderName, 
        o.Price, 
        CONCAT(e.FirstName, ' ', e.LastName) AS EmployeeName, 
        w.Name AS WarehouseName, 
        s.Name AS StatusName 
    FROM [Order] o
    JOIN Employee e ON o.EmployeeID = e.EmployeeID
    JOIN Warehouse w ON o.WarehouseID = w.WarehouseID
    JOIN OrderStatus s ON o.OrderStatusID = s.OrderStatusID
    WHERE o.OrderID = ?
");

            $stmt->execute([$orderID]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                echo "<h2>Zamówienie #{$order['OrderID']}</h2>";
                echo "<p><strong>Nazwa:</strong> {$order['OrderName']}</p>";
                echo "<p><strong>Cena:</strong> {$order['Price']} zł</p>";
                echo "<p><strong>Pracownik:</strong> {$order['EmployeeName']}</p>";
                echo "<p><strong>Magazyn:</strong> {$order['WarehouseName']}</p>";
                echo "<p><strong>Status:</strong> {$order['StatusName']}</p>";
            } else {
                echo "<p>Zamówienie nie zostało znalezione.</p>";
            }
        } catch (Exception $e) {
            echo "<p>Błąd podczas ładowania szczegółów zamówienia: {$e->getMessage()}</p>";
        }
        ?>
        <a href="order_list.php" class="back">Wróć do listy zamówień</a>        
    </main>
    <footer>
        <p>© 2025 System zarządzania zamówieniami. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
