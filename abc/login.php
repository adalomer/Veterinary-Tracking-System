<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute SQL statement
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session
        $_SESSION['user_id'] = $user['id'];

        // Check if user is admin
        if ($user['is_admin']) {
            $_SESSION['is_admin'] = true;
            header("Location: admin_panel.php"); // Redirect to admin panel
        } else {
            header("Location: index.php"); // Redirect to user dashboard
        }
        exit();
    } else {
        // Invalid credentials
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş - Veteriner Takip Sistemi</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Veteriner takip sistemi - Giriş</h1>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="login.php" method="post">
            <label for="username">Kullanıcı adı:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Giriş">
            <a href="register.php" class="register-button">Kayıt ol</a>
        </form>
    </div>
	<footer>
        <p>&copy; 2025 Ömer Ali Adalı tarafından tasarlandı.</p>
    </footer>
</body>
</html>

