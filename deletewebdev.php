<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/core/models.php';

requireLogin();
$gameDevId = filter_input(INPUT_GET, 'game_dev_id', FILTER_VALIDATE_INT);
if (!$gameDevId) {
    http_response_code(400);
    exit('A valid game developer ID is required.');
}

$gameDev = getGameDevByID($pdo, $gameDevId);
if (!$gameDev) {
    http_response_code(404);
    exit('Game developer not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="page-nav"><a href="index.php">Back to game developers</a></nav>
    <h1>Are you sure you want to delete this user?</h1>
    <div class="container" style="border-style: solid; height: 400px;">
        <h2>First Name: <?= htmlspecialchars($gameDev['firstname'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Last Name: <?= htmlspecialchars($gameDev['lastname'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Date of Birth: <?= htmlspecialchars($gameDev['date_of_birth'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Specialization: <?= htmlspecialchars($gameDev['specialization'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Date Added: <?= htmlspecialchars($gameDev['date_added'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Added By: <?= htmlspecialchars($gameDev['added_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Last Updated By: <?= htmlspecialchars($gameDev['updated_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Last Updated: <?= htmlspecialchars($gameDev['last_updated'] ?? 'Not available', ENT_QUOTES, 'UTF-8') ?></h2>

        <div class="deleteBtn" style="float: right; margin-right: 18px;">
            <form action="core/handleForms.php?game_dev_id=<?= $gameDevId ?>" method="POST">
                <input type="submit" name="deleteGameDevBtn" value="Delete">
            </form>
        </div>
    </div>
</body>
</html>
