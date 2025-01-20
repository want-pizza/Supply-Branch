<?php
try {
    // Підключення до бази даних
    $conn = new PDO("sqlsrv:server=DESKTOP-1D7L568;Database=StoreManagement");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
     catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
}
?>
