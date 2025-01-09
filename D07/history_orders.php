<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia zamówień</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Historia zamówień</h1>

    <!-- Formularz filtracji -->
    <form id="filterForm">
        <label for="employee">Pracownik:</label>
        <input type="text" id="employee" name="employee">

        <label for="warehouse">Magazyn:</label>
        <input type="text" id="warehouse" name="warehouse">

        <label for="date_from">Data od:</label>
        <input type="date" id="date_from" name="date_from">

        <label for="date_to">Data do:</label>
        <input type="date" id="date_to" name="date_to">

        <button type="submit">Filtruj</button>
    </form>

    <!-- Tabela wyników -->
    <table id="ordersTable">
        <thead>
            <tr>
                <th>#</th>
                <th>Nazwa zamówienia</th>
                <th>Cena</th>
                <th>Data</th>
                <th>Pracownik</th>
                <th>Magazyn</th>
                <th>Produkty</th>
            </tr>
        </thead>
        <tbody>
            <!-- Dane będą ładowane przez AJAX -->
        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            // Funkcja ładowania danych
            function loadOrders(filters = {}) {
                $.ajax({
                    url: 'fetching_history_orders.php',
                    method: 'GET',
                    data: filters,
                    success: function (response) {
                        const tbody = $('#ordersTable tbody');
                        tbody.empty();

                        if (response.length > 0) {
                            response.forEach(order => {
                                const row = `
                                    <tr>
                                        <td>${order.OrderID}</td>
                                        <td>${order.OrderName}</td>
                                        <td>${order.OrderPrice} zł</td>
                                        <td>${order.OrderDate}</td>
                                        <td>${order.EmployeeName}</td>
                                        <td>${order.WarehouseName}</td>
                                        <td>${order.Products}</td>
                                    </tr>
                                `;
                                tbody.append(row);
                            });
                        } else {
                            tbody.append('<tr><td colspan="7">Brak zamówień zgodnych z wybranymi kryteriami.</td></tr>');
                        }
                    },
                    error: function (xhr, status, error) {
                         alert(`Wystąpił błąd: ${xhr.responseText || status || error}`);
                        console.error(`Błąd AJAX: ${xhr.responseText || status || error}`);
                    }
                });
            }

            // Ładowanie danych po załadowaniu strony
            loadOrders();

            // Obsługa formularza filtracji
            $('#filterForm').on('submit', function (e) {
                e.preventDefault();

                const filters = {
                    employee: $('#employee').val(),
                    warehouse: $('#warehouse').val(),
                    date_from: $('#date_from').val(),
                    date_to: $('#date_to').val()
                };

                loadOrders(filters);
            });
        });
    </script>
</body>
</html>
