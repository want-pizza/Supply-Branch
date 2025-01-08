<?php
// Этот файл генерирует интерфейс страницы "Zarządzanie dostawcami"
// Пожалуйста, настройте сервер, чтобы поддерживать работу с PHP (например, используя XAMPP или другие серверы).

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie dostawcami</title>
    <link rel="stylesheet" type="text/css" href="./styles.css">
</head>
<body>
    <header>
        <h1>Zarządzanie dostawcami</h1>
        <h2>Ocena i wybór najlepszych ofert dostawców</h2>
    </header>

    <!-- Панель навигации -->
    <nav>
        <ul>
            <li><a href="#">Strona główna</a></li>
            <li><a href="#">Zarządzanie produktami</a></li>
            <li><a href="#">Zarządzanie dostawcami</a></li>
            <li><a href="#">Raporty</a></li>
            <li><a href="#">Ustawienia</a></li>
        </ul>
    </nav>

    <!-- Фильтры для поиска поставщиков -->
    <section id="filters">
        <h3>Filtruj dostawców</h3>
        <form method="get">
            <label for="name">Nazwa dostawcy:</label>
            <input type="text" id="name" name="name" placeholder="Wpisz nazwę dostawcy">

            <label for="rating">Ocena:</label>
            <input type="number" id="rating" name="rating" min="1" max="10" placeholder="Wybierz ocenę">

            <label for="delivery">Terminowość dostaw:</label>
            <select id="delivery" name="delivery">
                <option value="wszystkie">Wszystkie</option>
                <option value="terminowa">Terminowa</option>
                <option value="spóźniona">Spóźniona</option>
            </select>

            <button type="submit">Szukaj</button>
        </form>
    </section>

    <!-- Список поставщиков -->
    <section id="suppliers-list">
        <h3>Lista dostawców</h3>
        <table>
            <thead>
                <tr>
                    <th>Nazwa dostawcy</th>
                    <th>NIP</th>
                    <th>Ocena</th>
                    <th>Terminowość dostaw</th>
                    <th>Jakość współpracy</th>
                    <th>Status współpracy</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                <!-- Пример записи поставщика -->
                <tr>
                    <td>Firma ABC</td>
                    <td>123-456-78-90</td>
                    <td>8</td>
                    <td>Terminowa</td>
                    <td>Dobra</td>
                    <td>Aktywna</td>
                    <td>
                        <button onclick="viewDetails()">Szczegóły</button>
                        <button onclick="rateSupplier()">Ocena</button>
                        <button onclick="endCooperation()">Zakończ współpracę</button>
                    </td>
                </tr>
                <tr>
                    <td>Firma XYZ</td>
                    <td>987-654-32-10</td>
                    <td>6</td>
                    <td>Spóźniona</td>
                    <td>Średnia</td>
                    <td>Aktywna</td>
                    <td>
                        <button onclick="viewDetails()">Szczegóły</button>
                        <button onclick="rateSupplier()">Ocena</button>
                        <button onclick="endCooperation()">Zakończ współpracę</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <!-- Модальные окна для оценки и подробностей -->
    <div id="modal" style="display:none;">
        <h3>Formularz oceny dostawcy</h3>
        <form>
            <label for="supplier-rating">Ocena dostawcy:</label>
            <input type="number" id="supplier-rating" name="supplier-rating" min="1" max="10">
            <br>
            <label for="supplier-feedback">Komentarz:</label>
            <textarea id="supplier-feedback" name="supplier-feedback" rows="4"></textarea>
            <br>
            <button type="submit">Zapisz ocenę</button>
            <button type="button" onclick="closeModal()">Anuluj</button>
        </form>
    </div>

    <!-- Футер -->
    <footer>
        <ul>
            <li><a href="#">Polityka prywatności</a></li>
            <li><a href="#">Regulamin</a></li>
            <li><a href="#">Kontakt z administratorem</a></li>
        </ul>
    </footer>

    <!-- Скрипты для модальных окон -->
    <script>
        function viewDetails() {
            alert("Szczegóły dostawcy...");
        }

        function rateSupplier() {
            document.getElementById("modal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("modal").style.display = "none";
        }

        function endCooperation() {
            if (confirm("Czy na pewno chcesz zakończyć współpracę z tym dostawcą?")) {
                alert("Współpraca została zakończona.");
            }
        }
    </script>
</body>
</html>
