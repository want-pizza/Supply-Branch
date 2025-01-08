<?php
$password = '1234'; // Тестовый пароль
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

echo $hashedPassword;
?>
