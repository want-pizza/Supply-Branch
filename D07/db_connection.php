<?php
try {
    // Підключення до бази даних
    $conn = new PDO("sqlsrv:server=DESKTOP-49IMCI6\SQLEXPRESS;Database=StoreManagement");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
     catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
}
?>
