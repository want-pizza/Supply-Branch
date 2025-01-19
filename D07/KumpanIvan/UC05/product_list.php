<?php
require_once("db_connect.php");

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$sql = "SELECT p.ProductID, p.Name, p.Price, c.Name AS Category, u.Name AS Unit, s.Name AS Producer
        FROM Product p
        JOIN ProductCategory c ON p.CategoryID = c.CategoryID
        JOIN Unit u ON p.UnitID = u.UnitID
        JOIN Supplier s ON p.ProducerID = s.SupplierID
        ORDER BY p.Name
        OFFSET :offset ROWS FETCH NEXT :limit ROWS ONLY";
$stmt = $polaczenie->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalProducts = $polaczenie->query("SELECT COUNT(*) FROM dbo.Product")->fetchColumn();
$totalPages = ceil($totalProducts / $limit);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista produktów</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Lista produktów</h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="add_product_form.php">Dodaj produkt</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Nazwa</th>
                    <th>Kategoria</th>
                    <th>Jednostka</th>
                    <th>Dostawca</th>
                    <th>Cena</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['Name']) ?></td>
                        <td><?= htmlspecialchars($product['Category']) ?></td>
                        <td><?= htmlspecialchars($product['Unit']) ?></td>
                        <td><?= htmlspecialchars($product['Producer']) ?></td>
                        <td><?= htmlspecialchars($product['Price']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" <?= $i === $page ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </main>
    <footer>
        <p>© 2025 Zarządzanie produktami. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
