<header><link rel="stylesheet" href="style.css"></header>
<body>
<form id="filterForm" method="POST">
    <select name="id_magazynu">
        <option value="">Виберіть склад</option>
        <!-- Опції будуть заповнені динамічно -->
    </select>
    <select name="id_towaru">
        <option value="">Виберіть товар</option>
        <!-- Опції будуть заповнені динамічно -->
    </select>
    <button type="submit">Фільтрувати</button>
</form>
<table id="inventoryTable">
    <thead>
        <tr>
            <th>Товар</th>
            <th>Мінімальний рівень</th>
            <th>Максимальний рівень</th>
            <th>Склад</th>
            <th>Поточний рівень запасів</th>
        </tr>
    </thead>
    <tbody>
        <!-- Дані будуть заповнюватися динамічно -->
    </tbody>
</table>
</body>
