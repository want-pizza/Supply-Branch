<?php
require_once("db_connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $producerID = $_POST['producer_id'];
    $unitID = $_POST['unit_id'];
    $categoryID = $_POST['category_id'];
    $description = isset($_POST['description']) && !empty(trim($_POST['description'])) ? $_POST['description'] : null;

    if (!$name || !$price || !$producerID || !$unitID || !$categoryID) {
        die("<p class='msg error'>Wszystkie wymagane pola muszą być wypełnione.</p>");
    }

    try {
        $sql = "INSERT INTO dbo.Product (Name, Price, ProducerID, UnitID, CategoryID, Description) 
                VALUES (:Name, :Price, :ProducerID, :UnitID, :CategoryID, :Description)";
        $stmt = $polaczenie->prepare($sql);

        $stmt->bindParam(':Name', $name);
        $stmt->bindParam(':Price', $price);
        $stmt->bindParam(':ProducerID', $producerID);
        $stmt->bindParam(':UnitID', $unitID);
        $stmt->bindParam(':CategoryID', $categoryID);
        $stmt->bindParam(':Description', $description, PDO::PARAM_NULL);

        $stmt->execute();
        $message = "Produkt <strong>$name</strong> został pomyślnie dodany.";
    } catch (Exception $e) {
        $message = "Błąd: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wynik dodawania produktu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Wynik dodawania produktu</h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="product_list.php">Lista produktów</a></li>
                <li><a href="add_product_form.php">Dodaj produkt</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Komunikat o wyniku -->
        <p class="msg <?= isset($message) && strpos($message, 'Błąd') === false ? 'success' : 'error' ?>">
            <?= $message ?>
        </p>

        <!-- Przycisk powrotu do formularza -->
        <a href="add_product_form.php" class="back-button">Dodaj kolejny produkt</a>
    </main>
    <footer>
        <p>© 2025 Zarządzanie produktami. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
