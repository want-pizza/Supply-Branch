<header>
    <link rel="stylesheet" href="style.css">
</header>
<body>
<form method="POST" action="generate_report.php">
    <input type="hidden" name="id_magazynu" value="<?php echo $_POST['id_magazynu']; ?>">
    <input type="hidden" name="id_towaru" value="<?php echo $_POST['id_towaru']; ?>">
    <button type="submit">Завантажити звіт у PDF</button>
</form>
</body>
