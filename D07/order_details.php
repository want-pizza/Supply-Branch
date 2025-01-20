<?php
require_once 'db_connection.php';

if (!isset($_GET['order_id'])) {
    die("Brak ID zamówienia.");
}

$order_id = $_GET['order_id'];

// Pobierz listę produktów dla konkretnego zamówienia
$stmt = $conn->prepare("
    SELECT 
        p.Name AS ProductName,
        p.Price AS ProductPrice,
        op.Quantity AS ProductQuantity
    FROM 
        Order_Product op
    JOIN 
        Product p
    ON 
        op.ProductID = p.ProductID
    WHERE 
        op.OrderID = :order_id
");
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szczegóły zamówienia</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Produkty w zamówieniu</h1>

    <h2>Lista produktów</h2>
    <table>
        <thead>
            <tr>
                <th>Nazwa produktu</th>
                <th>Cena za sztukę</th>
                <th>Ilość</th>
                <th>Łączna cena</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['ProductName']) ?></td>
                    <td><?= htmlspecialchars($product['ProductPrice']) ?> zł</td>
                    <td><?= htmlspecialchars($product['ProductQuantity']) ?></td>
                    <td><?= htmlspecialchars($product['ProductPrice'] * $product['ProductQuantity']) ?> zł</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
