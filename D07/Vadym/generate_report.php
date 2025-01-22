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
$totalOrders = 0;
$totalQuantity = 0;
$uniqueSuppliers = [];

// Формирование SQL-запроса для фильтрации по дате отгрузки (ShippingDate) и доставки (DeliveryDate)
$query = "SELECT o.OrderID, o.Name AS OrderName, p.Name AS ProductName, op.Quantity, s.Name AS SupplierName, op.ShippingDate, op.DeliveryDate
          FROM [Order] o
          INNER JOIN Order_Product op ON o.OrderID = op.OrderID
          INNER JOIN Product p ON op.ProductID = p.ProductID
          INNER JOIN Supplier s ON op.SupplierID = s.SupplierID
          WHERE (op.ShippingDate BETWEEN ? AND ? OR op.DeliveryDate BETWEEN ? AND ?)";
          
// Если есть поставщик, добавляем фильтрацию по нему
if (!empty($supplierID)) {
    $query .= " AND op.SupplierID = ?";
}

// Подготовка запроса
if (!empty($supplierID)) {
    $stmt = sqlsrv_prepare($polaczenie, $query, array($startDate, $endDate, $startDate, $endDate, $supplierID));
} else {
    $stmt = sqlsrv_prepare($polaczenie, $query, array($startDate, $endDate, $startDate, $endDate));
}

// Выполнение запроса
if (sqlsrv_execute($stmt)) {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // Преобразуем даты в строковый формат, если они являются объектами DateTime
        $row['ShippingDate'] = $row['ShippingDate'] ? $row['ShippingDate']->format('Y-m-d') : '';
        $row['DeliveryDate'] = $row['DeliveryDate'] ? $row['DeliveryDate']->format('Y-m-d') : '';
        $results[] = $row;

        // Статистика
        $totalOrders++;
        $totalQuantity += $row['Quantity'];
        $uniqueSuppliers[$row['SupplierName']] = true;
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

    // Добавление даты генерации отчёта
    $html .= "<p><strong>Data wygenerowania raportu:</strong> " . date('Y-m-d H:i:s') . "</p>";
    $html .= "<p><strong>Okres raportu:</strong> $startDate - $endDate</p>";

    // Добавление статистики
    $html .= "<p><strong>Statystyki:</strong></p>";
    $html .= "<ul>";
    $html .= "<li><strong>Liczba zamówien:</strong> $totalOrders</li>";
    $html .= "<li><strong>Laczna liczba produktów:</strong> $totalQuantity</li>";
    $html .= "<li><strong>Liczba unikalnych dostawców:</strong> " . count($uniqueSuppliers) . "</li>";
    $html .= "</ul>";

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

    // Заголовок отчёта
    $sheet->setCellValue('A1', 'Raport')
          ->mergeCells('A1:G1')
          ->setCellValue('A2', 'Data wygenerowania raportu:')
          ->setCellValue('B2', date('Y-m-d H:i:s'))
          ->setCellValue('A3', 'Okres raportu:')
          ->setCellValue('B3', "$startDate - $endDate");

    // Статистика
    $sheet->setCellValue('A5', 'Statystyki:')
          ->setCellValue('A6', 'Liczba zamówien:')
          ->setCellValue('B6', $totalOrders)
          ->setCellValue('A7', 'Laczna liczba produktów:')
          ->setCellValue('B7', $totalQuantity)
          ->setCellValue('A8', 'Liczba unikalnych dostawców:')
          ->setCellValue('B8', count($uniqueSuppliers));

    $sheet->setCellValue('A10', 'OrderID')
          ->setCellValue('B10', 'Order Name')
          ->setCellValue('C10', 'Product')
          ->setCellValue('D10', 'Quantity')
          ->setCellValue('E10', 'Supplier')
          ->setCellValue('F10', 'Shipping Date')
          ->setCellValue('G10', 'Delivery Date');

    $rowIndex = 11;

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
        $sheet->setCellValue("A11", "Brak danych");
    }

    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="Raport.xlsx"');
    $writer->save('php://output');
} else {
    echo "Niepoprawny format raportu.";
}
?>
