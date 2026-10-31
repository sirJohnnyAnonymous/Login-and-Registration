<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/models.php';
requireLogin('../login.php');

$user = getUserByID($pdo, $_SESSION['user_id']);
if (!$user) {
    http_response_code(404);
    exit('User not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your account | Game Dev Projects</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <nav class="page-nav"><a href="../index.php">Back to game developers</a></nav>
    <h1>Your account</h1>
    <h2>Username: <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></h2>
    <h2>Date joined: <?= htmlspecialchars($user['date_added'], ENT_QUOTES, 'UTF-8') ?></h2>
</body>
</html>
