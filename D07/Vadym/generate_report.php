<?php
require 'db_connect.php'; // Подключение к базе данных

// Подключаем автозагрузчик Composer
require 'vendor/autoload.php';

// Импортируем нужные классы для работы с Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Подключаем соединение
$polaczenie = require 'db_connect.php';

// Проверка подключения
if ($polaczenie === false) {
    die("Ошибка подключения к базе данных.");
}

// Параметры из формы
$startDate = $_POST['start-date'];
$endDate = $_POST['end-date'];
$supplierID = $_POST['supplier'];
$format = $_POST['format'];

// Инициализация массива для хранения результатов
$results = [];

// Формирование SQL-запроса для фильтрации по дате отгрузки (ShippingDate) и доставки (DeliveryDate)
$query = "SELECT o.OrderID, o.Name AS OrderName, p.Name AS ProductName, op.Quantity, s.Name AS SupplierName, op.ShippingDate, op.DeliveryDate
          FROM [Order] o
          INNER JOIN Order_Product op ON o.OrderID = op.OrderID
          INNER JOIN Product p ON op.ProductID = p.ProductID
          INNER JOIN Supplier s ON op.SupplierID = s.SupplierID
          WHERE op.ShippingDate BETWEEN ? AND ? OR op.DeliveryDate BETWEEN ? AND ?";

// Если есть поставщик, добавляем фильтрацию по нему
if (!empty($supplierID)) {
    $query .= " AND op.SupplierID = ?";
}

// Подготовка запроса
$stmt = sqlsrv_prepare($polaczenie, $query, array($startDate, $endDate, $startDate, $endDate, $supplierID));

// Выполнение запроса
if (sqlsrv_execute($stmt)) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Преобразуем даты в строковый формат, если они являются объектами DateTime
        $row['ShippingDate'] = $row['ShippingDate'] ? $row['ShippingDate']->format('Y-m-d') : '';
        $row['DeliveryDate'] = $row['DeliveryDate'] ? $row['DeliveryDate']->format('Y-m-d') : '';
        $results[] = $row;
    }
} else {
    // Получаем и выводим подробности ошибки
    die("Ошибка выполнения запроса: " . print_r(sqlsrv_errors(), true));
}

// Генерация отчёта
if ($format === 'pdf') {
    require 'vendor/autoload.php'; // Подключение библиотеки TCPDF
    $pdf = new TCPDF();
    $pdf->AddPage();
    $html = "<h1>Raport</h1>";
    $html .= "<table border='1'><thead><tr><th>OrderID</th><th>Order Name</th><th>Product</th><th>Quantity</th><th>Supplier</th><th>Shipping Date</th><th>Delivery Date</th></tr></thead><tbody>";
    
    // Проверка на наличие данных в $results
    if (!empty($results)) {
        foreach ($results as $row) {
            $html .= "<tr><td>{$row['OrderID']}</td><td>{$row['OrderName']}</td><td>{$row['ProductName']}</td><td>{$row['Quantity']}</td><td>{$row['SupplierName']}</td><td>{$row['ShippingDate']}</td><td>{$row['DeliveryDate']}</td></tr>";
        }
    } else {
        $html .= "<tr><td colspan='7'>Brak danych</td></tr>";
    }
    $html .= "</tbody></table>";
    $pdf->writeHTML($html);
    $pdf->Output('Raport.pdf', 'D');
} elseif ($format === 'excel') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'OrderID')
          ->setCellValue('B1', 'Order Name')
          ->setCellValue('C1', 'Product')
          ->setCellValue('D1', 'Quantity')
          ->setCellValue('E1', 'Supplier')
          ->setCellValue('F1', 'Shipping Date')
          ->setCellValue('G1', 'Delivery Date');

    $rowIndex = 2;

    // Проверка на наличие данных в $results
    if (!empty($results)) {
        foreach ($results as $row) {
            $sheet->setCellValue("A{$rowIndex}", $row['OrderID'])
                  ->setCellValue("B{$rowIndex}", $row['OrderName'])
                  ->setCellValue("C{$rowIndex}", $row['ProductName'])
                  ->setCellValue("D{$rowIndex}", $row['Quantity'])
                  ->setCellValue("E{$rowIndex}", $row['SupplierName'])
                  ->setCellValue("F{$rowIndex}", $row['ShippingDate'])
                  ->setCellValue("G{$rowIndex}", $row['DeliveryDate']);
            $rowIndex++;
        }
    } else {
        $sheet->setCellValue("A2", "Brak danych");
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Raport.xlsx"');
    $writer->save('php://output');
} else {
    echo "Niepoprawny format raportu.";
}
?>
