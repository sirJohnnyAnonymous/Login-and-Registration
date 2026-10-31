<?php
require_once __DIR__ . '/core/auth.php';
require_once __DIR__ . '/core/models.php';

requireLogin();
$projectId = (string) ($_GET['project_id'] ?? '');
$gameDevId = filter_input(INPUT_GET, 'game_dev_id', FILTER_VALIDATE_INT);
if ($projectId === '' || !$gameDevId) {
    http_response_code(400);
    exit('A valid project ID and game developer ID are required.');
}

$project = getProjectByID($pdo, $projectId);
if (!$project) {
    http_response_code(404);
    exit('Project not found.');
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
    <nav class="page-nav">
        <a href="viewprojects.php?game_dev_id=<?= $gameDevId ?>">Back to projects</a>
        <span>Signed in as <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong></span>
    </nav>
    <h1>Are you sure you want to delete this project?</h1>
    <div class="container" style="border-style: solid; height: 400px;">
        <h2>Project Name: <?= htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Technologies Used: <?= htmlspecialchars($project['technologies_used'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Project Owner: <?= htmlspecialchars($project['project_owner'], ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Created By: <?= htmlspecialchars($project['added_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Last Updated By: <?= htmlspecialchars($project['updated_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></h2>
        <h2>Last Updated: <?= htmlspecialchars($project['last_updated'] ?? 'Not available', ENT_QUOTES, 'UTF-8') ?></h2>

        <div class="deleteBtn" style="float: right; margin-right: 10px;">
            <form action="core/handleForms.php?project_id=<?= rawurlencode($projectId) ?>&amp;game_dev_id=<?= $gameDevId ?>" method="POST">
                <input type="submit" name="deleteProjectBtn" value="Delete">
            </form>
        </div>
    </div>
</body>
</html>
