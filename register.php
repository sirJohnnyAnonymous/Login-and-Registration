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
    <title>Create account | Game Dev Projects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="auth-page">
    <main class="auth-card">
        <p class="eyebrow">Game Dev Projects</p>
        <h1>Create your account</h1>
        <p>Register to start managing projects.</p>
        <?php if ($message !== ''): ?>
            <p class="notice" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="core/handleForms.php" method="POST">
            <p>
                <label for="username">Username</label>
                <input type="text" name="username" id="username" minlength="3" maxlength="50" autocomplete="username" required>
            </p>
            <p>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" minlength="8" autocomplete="new-password" required>
                <small>Use at least 8 characters.</small>
            </p>
            <button type="submit" name="registerUserBtn">Create account</button>
        </form>
        <p class="auth-link">Already registered? <a href="login.php">Log in</a></p>
    </main>
</body>
</html>
