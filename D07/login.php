<?php
session_start(); // Стартуємо сесію

require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Отримуємо користувача з бази даних за логіном
    $stmt = $conn->prepare("SELECT UserID, [Password], [Role] FROM [User] WHERE [Login] = :login");
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Перевіряємо, чи користувач існує та чи введений пароль відповідає хешу
    if ($user && password_verify($password, $user['Password'])) {
        // Якщо пароль вірний, зберігаємо інформацію в сесії
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_role'] = $user['Role'];
    }        
        // Перенаправляємо користувача на іншу сторінку
        header("Location: history_orders.php");
        exit();
    } else {
        // Невірний логін або пароль
        echo "Неправильний логін або пароль.";
    }
?>
