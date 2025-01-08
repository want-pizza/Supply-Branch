<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once('tcpdf/tcpdf.php');
$conn = new PDO("sqlsrv:server=YOUR_SERVER;Database=SupplyManagement", "username", "password");

// Отримання даних для звіту
$where = [];
$params = [];

if (!empty($_POST['id_magazynu'])) {
    $where[] = "m.id_magazynu = :id_magazynu";
    $params[':id_magazynu'] = $_POST['id_magazynu'];
}
if (!empty($_POST['id_towaru'])) {
    $where[] = "t.id_towaru = :id_towaru";
    $params[':id_towaru'] = $_POST['id_towaru'];
}

$query = "SELECT 
            t.nazwa AS towar,
            t.min AS min_level,
            t.max AS max_level,
            m.nazwa_magazynu AS magazyn,
            m.stan_zapasow AS current_stock
          FROM Towar t
          JOIN Magazyn m ON t.id_towaru = m.id_magazynu";

if ($where) {
    $query .= " WHERE " . implode(" AND ", $where);
}

$stmt = $conn->prepare($query);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Генерація PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

$html = '<h1>Звіт по запасах</h1>';
$html .= '<table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Мінімальний рівень</th>
                    <th>Максимальний рівень</th>
                    <th>Склад</th>
                    <th>Поточний рівень</th>
                </tr>
            </thead>
            <tbody>';
foreach ($results as $row) {
    $html .= "<tr>
                <td>{$row['towar']}</td>
                <td>{$row['min_level']}</td>
                <td>{$row['max_level']}</td>
                <td>{$row['magazyn']}</td>
                <td>{$row['current_stock']}</td>
              </tr>";
}
$html .= '</tbody></table>';

$pdf->writeHTML($html);
$pdf->Output('inventory_report.pdf', 'D');
?>
