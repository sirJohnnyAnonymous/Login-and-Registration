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

$projects = getProjectsByGameDev($pdo, $gameDevId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - <?= htmlspecialchars($gameDev['firstname'] . ' ' . $gameDev['lastname'], ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="page-nav">
        <a href="index.php">Back to game developers</a>
        <span>Signed in as <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong></span>
    </nav>
    <h1>Projects by <?= htmlspecialchars($gameDev['firstname'] . ' ' . $gameDev['lastname'], ENT_QUOTES, 'UTF-8') ?></h1>

    <?php if ($projects): ?>
        <table>
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Technologies Used</th>
                    <th>Project Owner</th>
                    <th>Created By</th>
                    <th>Last Updated By</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?= htmlspecialchars($project['project_id'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['project_name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['technologies_used'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['project_owner'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['added_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['updated_by'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($project['last_updated'] ?? 'Not available', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="editproject.php?project_id=<?= rawurlencode($project['project_id']) ?>&amp;game_dev_id=<?= $gameDevId ?>">Edit</a>
                            <a href="deleteproject.php?project_id=<?= rawurlencode($project['project_id']) ?>&amp;game_dev_id=<?= $gameDevId ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No projects have been added for this game developer.</p>
    <?php endif; ?>

    <h2>Add a project</h2>
    <form action="core/handleForms.php?game_dev_id=<?= $gameDevId ?>" method="POST">
        <p>
            <label for="projectName">Project Name</label>
            <input type="text" name="projectName" id="projectName" maxlength="50" required>
        </p>
        <p>
            <label for="technologiesUsed">Technologies Used</label>
            <textarea name="technologiesUsed" id="technologiesUsed" required></textarea>
        </p>
        <button type="submit" name="insertNewProjectBtn">Add Project</button>
    </form>
</body>
</html>
