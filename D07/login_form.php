<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Test Form</title>
</head>
<body>
    <form method="POST" action="login.php">
        <label for="login">Username:</label>
        <input type="text" id="login" name="login" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Log in</button>
    </form>
</body>
</html>
