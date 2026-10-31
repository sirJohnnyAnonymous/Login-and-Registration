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
    <h1>Edit game developer</h1>
    <p>Added by <?= htmlspecialchars($gameDev['added_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($gameDev['date_added'], ENT_QUOTES, 'UTF-8') ?>.</p>
    <p>Last updated by <?= htmlspecialchars($gameDev['updated_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($gameDev['last_updated'] ?? 'Not available', ENT_QUOTES, 'UTF-8') ?>.</p>
    <form action="core/handleForms.php?game_dev_id=<?= $gameDevId ?>" method="POST">
        <p>
            <label for="firstName">First Name</label>
            <input type="text" name="firstName" value="<?= htmlspecialchars($gameDev['firstname'], ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="lastName">Last Name</label>
            <input type="text" name="lastName" id="lastName" value="<?= htmlspecialchars($gameDev['lastname'], ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="dateOfBirth">Date of Birth</label>
            <input type="date" name="dateOfBirth" id="dateOfBirth" value="<?= htmlspecialchars($gameDev['date_of_birth'], ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="specialization">Specialization</label>
            <input type="text" name="specialization" id="specialization" value="<?= htmlspecialchars($gameDev['specialization'], ENT_QUOTES, 'UTF-8') ?>" required>
            <button type="submit" name="editGameDevBtn">Save Changes</button>
        </p>
    </form>
</body>
</html>
