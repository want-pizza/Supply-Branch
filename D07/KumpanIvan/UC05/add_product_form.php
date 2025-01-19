<?php
include 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj produkt</title>
    <link rel="stylesheet" href="styles.css">
    <script>
    function validateForm(event) {
        event.preventDefault();

        const name = document.getElementById('name').value.trim();
        const price = document.getElementById('price').value.trim();
        const categoryID = document.getElementById('category_id').value.trim();
        const producerID = document.getElementById('producer_id').value.trim();
        const unitID = document.getElementById('unit_id').value.trim();

        if (!name || !price || !categoryID || !producerID || !unitID) {
            alert('Proszę wypełnić wszystkie wymagane pola.');
            return false;
        }

        if (isNaN(price)) {
            alert('Cena musi być wartością liczbową.');
            return false;
        }

        const confirmAdd = confirm('Czy na pewno chcesz dodać ten produkt?');
        if (confirmAdd) {
            document.getElementById('addProductForm').submit();
        }
    }
</script>
</head>
<body>
    <header>
        <h1>Dodawanie nowego produktu</h1>
        <nav>
            <ul>
                <li><a href="index.php">Strona główna</a></li>
                <li><a href="product_list.php">Lista produktów</a></li>
            </ul>
        </nav>
    </header>
    <main>
    <form id="addProductForm" action="add_product.php" method="post" onsubmit="validateForm(event)">
    <label for="name">Nazwa produktu:</label>
    <input type="text" id="name" name="name" required>

    <label for="price">Cena:</label>
    <input type="number" id="price" name="price" step="0.01" required>

    <label for="category_id">Kategoria:</label>
    <select id="category_id" name="category_id" required>
        <option value="">Wybierz kategorię</option>
        <?php
        try {
            $stmt = $polaczenie->query("SELECT CategoryID, Name FROM ProductCategory");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['CategoryID']}'>{$row['Name']}</option>";
            }
        } catch (Exception $e) {
            echo "<option value=''>Błąd ładowania kategorii</option>";
        }
        ?>
    </select>

    <label for="producer_id">Dostawca:</label>
    <select id="producer_id" name="producer_id" required>
        <option value="">Wybierz dostawcę</option>
        <?php
        try {
            $stmt = $polaczenie->query("SELECT SupplierID, Name FROM Supplier");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['SupplierID']}'>{$row['Name']}</option>";
            }
        } catch (Exception $e) {
            echo "<option value=''>Błąd ładowania dostawców</option>";
        }
        ?>
    </select>

    <label for="unit_id">Jednostka miary:</label>
    <select id="unit_id" name="unit_id" required>
        <option value="">Wybierz jednostkę miary</option>
        <?php
        try {
            $stmt = $polaczenie->query("SELECT UnitID, Name FROM Unit");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['UnitID']}'>{$row['Name']}</option>";
            }
        } catch (Exception $e) {
            echo "<option value=''>Błąd ładowania jednostek miary</option>";
        }
        ?>
    </select>

    <label for="description">Opis:</label>
    <textarea id="description" name="description" rows="3"></textarea>

    <button type="submit">Dodaj produkt</button>
</form>

    </main>
    <footer>
        <p>© 2025 Zarządzanie produktami. Wszelkie prawa zastrzeżone.</p>
    </footer>
</body>
</html>
