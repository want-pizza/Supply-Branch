<?php
// Параметры подключения к серверу базы данных
$serwer = "DESKTOP-9LEEGGO\SQLEXPRESS";  // Имя вашего SQL Server
$baza_danych = "StoreManagement";  // Название вашей базы данных

// Параметры подключения
$dane_polaczenia = array("Database" => $baza_danych);

// Пробуем подключиться к серверу базы данных
$polaczenie = sqlsrv_connect($serwer, $dane_polaczenia);

// Проверяем, удалось ли подключиться
if ($polaczenie === false) {
    // Если подключение не удалось, выводим ошибку и завершаем выполнение скрипта
    die("<p class='msg error'>Połączenie z serwerem baz danych $serwer nie powiodło się.</p>");
}

// Возвращаем соединение для использования в других скриптах
return $polaczenie;
?>
