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
    <title>Edit Project</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="page-nav">
        <a href="viewprojects.php?game_dev_id=<?= $gameDevId ?>">Back to projects</a>
        <span>Signed in as <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong></span>
    </nav>
    <h1>Edit Project</h1>
    <p>Created by <?= htmlspecialchars($project['added_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($project['date_added'], ENT_QUOTES, 'UTF-8') ?>.</p>
    <p>Last updated by <?= htmlspecialchars($project['updated_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?> on <?= htmlspecialchars($project['last_updated'] ?? 'Not available', ENT_QUOTES, 'UTF-8') ?>.</p>
    <form action="core/handleForms.php?project_id=<?= rawurlencode($projectId) ?>&amp;game_dev_id=<?= $gameDevId ?>" method="POST">
        <p>
            <label for="projectName">Project Name</label>
            <input type="text" name="projectName" id="projectName" maxlength="50" value="<?= htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="technologiesUsed">Technologies Used</label>
            <textarea name="technologiesUsed" id="technologiesUsed" required><?= htmlspecialchars($project['technologies_used'], ENT_QUOTES, 'UTF-8') ?></textarea>
        </p>
        <button type="submit" name="editProjectBtn">Save Changes</button>
    </form>
</body>
</html>
