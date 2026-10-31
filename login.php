<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/core/models.php';
requireGuest();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Game Dev Projects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <p class="eyebrow">Game Dev Projects</p>
        <h1>Welcome back</h1>
        <p>Log in to manage your game developers and projects.</p>
        <?php if ($message !== ''): ?>
            <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="core/handleForms.php" method="POST">
            <p>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" maxlength="50" autocomplete="username" required>
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="current-password" required>
            </p>
            <button type="submit" name="loginUserBtn">Log in</button>
        </form>
        <p class="auth-link">Don't have an account? <a href="register.php">Create one</a></p>
    </main>
</body>
</html>
