<?php
// Połączenie z serwerem
$serwer = "LENOVO\SQL1";
$baza_danych = "StoreManagement";

try {
    $polaczenie = new PDO("sqlsrv:server=$serwer;Database=$baza_danych");

    // Obsługa wyjątków
    $polaczenie->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $polaczenie->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
} catch (Exception $e) {
    print("<p class='msg error'>Połączenie z serwerem baz danych $serwer nie powiodło się.<br>
    Szczegóły: " . $e->getMessage() . "</p>");
}
?>