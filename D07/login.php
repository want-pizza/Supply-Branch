<?php
session_start(); // Стартуємо сесію

try {
    // Підключення до бази даних
    $conn = new PDO("sqlsrv:server=DESKTOP-49IMCI6\SQLEXPRESS;Database=SupplyManagement");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Перевіряємо, чи це POST-запит
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];

        // Отримуємо користувача з бази даних за логіном
        $stmt = $conn->prepare("SELECT id_uzytkownika, haslo, rola FROM Uzytkownicy WHERE login = :login");
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Перевіряємо, чи користувач існує та чи введений пароль відповідає хешу
        if ($user && password_verify($password, $user['haslo'])) {
            // Якщо пароль вірний, зберігаємо інформацію в сесії
            $_SESSION['user_id'] = $user['id_uzytkownika'];
            $_SESSION['user_role'] = $user['rola'];

            // Перенаправляємо користувача на іншу сторінку
            header("Location: filtration_form.php");
            exit();
        } else {
            // Невірний логін або пароль
            echo "Неправильний логін або пароль.";
        }
    }
} catch (PDOException $e) {
    echo "Помилка підключення до бази даних: " . $e->getMessage();
}
?>
