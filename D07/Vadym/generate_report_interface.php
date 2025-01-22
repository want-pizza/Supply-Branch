<?php
require 'db_connect.php'; // Подключение к базе данных

// Получение списка поставщиков
$query = "SELECT SupplierID, Name FROM Supplier";
$suppliers = [];
try {
    $stmt = sqlsrv_query($polaczenie, $query);  // Используем sqlsrv_query для выполнения запроса
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $suppliers[] = $row; // Заполняем массив поставщиков
    }
} catch (Exception $e) {
    echo "Ошибка при выполнении запроса: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generowanie raportów</title>
    <link rel="stylesheet" href="styles.css"> <!-- Подключение CSS -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Generowanie raportów</h1>
            <h2>Tworzenie szczegółowych raportów</h2>
        </header>

        <section id="report-form">
            <h3>Wybierz kryteria do raportu</h3>
            <form method="post" action="generate_report.php">
                <div class="form-group">
                    <label for="start-date">Data początkowa:</label>
                    <input type="date" id="start-date" name="start-date" required>
                </div>

                <div class="form-group">
                    <label for="end-date">Data końcowa:</label>
                    <input type="date" id="end-date" name="end-date" required>
                </div>

                <div class="form-group">
                    <label for="supplier">Dostawca:</label>
                    <select id="supplier" name="supplier">
                        <option value="">Wszyscy dostawcy</option>
                        <?php
                        // Отображение списка поставщиков
                        foreach ($suppliers as $supplier) {
                            echo "<option value=\"{$supplier['SupplierID']}\">{$supplier['Name']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="format">Format raportu:</label>
                    <select id="format" name="format">
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                </div>

                <button type="submit" class="btn">Generuj raport</button>
            </form>
        </section>
    </div>
</body>
</html>
