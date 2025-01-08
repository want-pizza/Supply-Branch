<?php
// Этот файл генерирует интерфейс страницы "Generowanie raportów dotyczących wydatków"
// Пожалуйста, настройте сервер для поддержки работы с PHP (например, используйте XAMPP или другие серверы).

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generowanie raportów dotyczących wydatków</title>
    <link rel="stylesheet" href="styles.css"> <!-- Подключение стилей (по желанию) -->
</head>
<body>
    <header>
        <h1>Generowanie raportów dotyczących wydatków</h1>
        <h2>Tworzenie szczegółowych raportów kosztów zakupu towarów</h2>
    </header>

    <!-- Панель навигации -->
    <nav>
        <ul>
            <li><a href="#">Strona główna</a></li>
            <li><a href="#">Zarządzanie produktami</a></li>
            <li><a href="#">Zarządzanie dostawcami</a></li>
            <li><a href="#">Generowanie raportów</a></li> <!-- Текущая страница -->
            <li><a href="#">Ustawienia</a></li>
        </ul>
    </nav>

    <!-- Форма для выбора параметров отчета -->
    <section id="report-form">
        <h3>Wybierz kryteria do raportu</h3>
        <form method="post" action="generate_report.php">
            <label for="start-date">Data początkowa:</label>
            <input type="date" id="start-date" name="start-date" required>

            <label for="end-date">Data końcowa:</label>
            <input type="date" id="end-date" name="end-date" required>

            <label for="supplier">Dostawca:</label>
            <input type="text" id="supplier" name="supplier" placeholder="Wpisz nazwę dostawcy">

            <label for="format">Format raportu:</label>
            <select id="format" name="format">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
            </select>

            <button type="submit">Generuj raport</button>
        </form>
    </section>

    <!-- Вывод результата отчета (если есть данные) -->
    <section id="report-result">
        <h3>Wygenerowany raport</h3>
        <!-- Таблица с результатами отчета (пример статичных данных) -->
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Dostawca</th>
                    <th>Kwota</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-01-01</td>
                    <td>Firma ABC</td>
                    <td>500 PLN</td>
                    <td>Opłacono</td>
                </tr>
                <tr>
                    <td>2025-01-05</td>
                    <td>Firma XYZ</td>
                    <td>200 PLN</td>
                    <td>Opłacono</td>
                </tr>
            </tbody>
        </table>

        <!-- Пример графика -->
        <h4>Wykres wydatków</h4>
        <img src="chart-example.png" alt="Wykres wydatków">
    </section>

    <!-- Панель уведомлений -->
    <section id="notifications">
        <!-- Здесь будут отображаться уведомления, например, об ошибках или успехах -->
        <p>Raport został wygenerowany pomyślnie!</p>
    </section>

    <!-- Футер -->
    <footer>
        <ul>
            <li><a href="#">Polityka prywatności</a></li>
            <li><a href="#">Regulamin</a></li>
            <li><a href="#">Kontakt z administratorem</a></li>
        </ul>
    </footer>

    <!-- Скрипты для динамических взаимодействий -->
    <script>
        // Пример обработки ошибок (если есть)
        function showError(message) {
            document.getElementById('notifications').innerHTML = '<p>' + message + '</p>';
        }

        // Пример закрытия уведомлений
        function closeNotifications() {
            document.getElementById('notifications').style.display = 'none';
        }
    </script>
</body>
</html>
