<?php
require 'db_connect.php'; // Подключение к базе данных

// Получение списка поставщиков с данными о времени доставки
$query = "
    SELECT Supplier.SupplierID, Supplier.Name, Supplier.PhoneNumber, 
           AVG(DATEDIFF(DAY, op.ShippingDate, op.DeliveryDate)) AS AvgDeliveryTime
    FROM Supplier
    LEFT JOIN Order_Product op ON Supplier.SupplierID = op.SupplierID
    GROUP BY Supplier.SupplierID, Supplier.Name, Supplier.PhoneNumber
";
$suppliers = [];
try {
    $stmt = sqlsrv_query($polaczenie, $query);  // Используем sqlsrv_query для выполнения запроса
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $suppliers[] = $row; // Заполняем массив поставщиков с их данными
    }
} catch (Exception $e) {
    echo "Ошибка при выполнении запроса: " . $e->getMessage();
}

$message = ''; // Для вывода сообщения о принятом решении

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supplier-id']) && isset($_POST['action'])) {
        $supplierId = $_POST['supplier-id'];
        $action = $_POST['action'];

        // Получаем данные поставщика для сообщения
        $supplierName = '';
        $supplierPhone = '';
        foreach ($suppliers as $supplier) {
            if ($supplier['SupplierID'] == $supplierId) {
                $supplierName = $supplier['Name'];
                $supplierPhone = $supplier['PhoneNumber'];
                break;
            }
        }

        // Имитация отправки уведомления или записи решения
        $message = "Decyzja o " . ($action === 'continue' ? "kontynuacji" : "zakończeniu") . " współpracy z dostawcą $supplierName (ID: $supplierId, Telefon: $supplierPhone) została zapisana i powiadomienie wysłane do dostawcy.";
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie dostawcami</title> <!-- Обновленный заголовок -->
    <link rel="stylesheet" href="styles.css"> <!-- Подключение CSS -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Zarządzanie dostawcami</h1> <!-- Обновленный заголовок -->
            <h2>Wybór dostawcy i podjęcie decyzji</h2> <!-- Обновленный заголовок -->
        </header>

        <section id="report-form">
            <h3>Wybierz dostawcę i podejmij decyzję</h3>
            <form method="post">
                <div class="form-group">
                    <label for="supplier">Dostawca:</label>
                    <select id="supplier" name="supplier-id" required>
                        <option value="">Wybierz dostawcę</option>
                        <?php
                        // Отображение списка поставщиков с их средним временем доставки
                        foreach ($suppliers as $supplier) {
                            echo "<option value=\"{$supplier['SupplierID']}\">{$supplier['Name']} (Średni czas dostawy: {$supplier['AvgDeliveryTime']} dni)</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="action">Decyzja:</label>
                    <select id="action" name="action" required>
                        <option value="continue">Kontynuować współpracę</option>
                        <option value="end">Zakończyć współpracę</option>
                    </select>
                </div>

                <button type="submit" class="btn">Zapisz decyzję</button>
            </form>

            <!-- Если есть сообщение, показываем его -->
            <?php if ($message): ?>
                <div class="message">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
        </section>
    </div>
</body>
</html>
