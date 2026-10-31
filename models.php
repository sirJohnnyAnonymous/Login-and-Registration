<?php
function insertGameDev($pdo, $firstname, $lastname, $dateOfBirth, $specialization, $userId)
{
    $sql = 'INSERT INTO game_dev (firstname, lastname, date_of_birth, specialization, added_by, updated_by)
            VALUES (?, ?, ?, ?, ?, ?)';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$firstname, $lastname, $dateOfBirth, $specialization, $userId, $userId]);
}

function getAllGameDevs($pdo)
{
    $sql = "SELECT game_dev.*,
                   creator.username AS added_by,
                   updater.username AS updated_by
            FROM game_dev
            LEFT JOIN user_passwords AS creator ON game_dev.added_by = creator.user_id
            LEFT JOIN user_passwords AS updater ON game_dev.updated_by = updater.user_id
            ORDER BY game_dev.game_dev_id";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGameDevByID($pdo, $gameDevId)
{
    $sql = "SELECT game_dev.*,
                   creator.username AS added_by,
                   updater.username AS updated_by
            FROM game_dev
            LEFT JOIN user_passwords AS creator ON game_dev.added_by = creator.user_id
            LEFT JOIN user_passwords AS updater ON game_dev.updated_by = updater.user_id
            WHERE game_dev.game_dev_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameDevId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateGameDev($pdo, $firstname, $lastname, $dateOfBirth, $specialization, $gameDevId, $userId)
{
    $sql = 'UPDATE game_dev
            SET firstname = ?, lastname = ?, date_of_birth = ?, specialization = ?, updated_by = ?
            WHERE game_dev_id = ?';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$firstname, $lastname, $dateOfBirth, $specialization, $userId, $gameDevId]);
}

function deleteGameDev($pdo, $gameDevId)
{
    $stmt = $pdo->prepare('DELETE FROM game_dev WHERE game_dev_id = ?');
    return $stmt->execute([$gameDevId]);
}

function getProjectsByGameDev($pdo, $gameDevId)
{
    $sql = "SELECT game_project.game_project_id AS project_id,
                   game_project.project_name,
                   game_project.technologies_used,
                   game_project.date_added,
                   game_project.last_updated,
                   creator.username AS added_by,
                   updater.username AS updated_by,
                   CONCAT(game_dev.firstname, ' ', game_dev.lastname) AS project_owner
            FROM game_project
            JOIN game_dev ON game_project.game_dev_id = game_dev.game_dev_id
            LEFT JOIN user_passwords AS creator ON game_project.added_by = creator.user_id
            LEFT JOIN user_passwords AS updater ON game_project.updated_by = updater.user_id
            WHERE game_project.game_dev_id = ?
            ORDER BY game_project.date_added DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$gameDevId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertProject($pdo, $projectName, $technologiesUsed, $gameDevId, $userId)
{
    $sql = 'INSERT INTO game_project
                (game_project_id, project_name, technologies_used, game_dev_id, added_by, updated_by, last_updated)
            VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        bin2hex(random_bytes(16)),
        $projectName,
        $technologiesUsed,
        $gameDevId,
        $userId,
        $userId
    ]);
}

function getProjectByID($pdo, $projectId)
{
    $sql = "SELECT game_project.game_project_id AS project_id,
                   game_project.project_name,
                   game_project.technologies_used,
                   game_project.date_added,
                   game_project.last_updated,
                   creator.username AS added_by,
                   updater.username AS updated_by,
                   CONCAT(game_dev.firstname, ' ', game_dev.lastname) AS project_owner
            FROM game_project
            JOIN game_dev ON game_project.game_dev_id = game_dev.game_dev_id
            LEFT JOIN user_passwords AS creator ON game_project.added_by = creator.user_id
            LEFT JOIN user_passwords AS updater ON game_project.updated_by = updater.user_id
            WHERE game_project.game_project_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$projectId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateProject($pdo, $projectName, $technologiesUsed, $projectId, $userId)
{
    $sql = 'UPDATE game_project
            SET project_name = ?, technologies_used = ?, updated_by = ?, last_updated = CURRENT_TIMESTAMP
            WHERE game_project_id = ?';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$projectName, $technologiesUsed, $userId, $projectId]);
}

function deleteProject($pdo, $projectId)
{
    $stmt = $pdo->prepare('DELETE FROM game_project WHERE game_project_id = ?');
    return $stmt->execute([$projectId]);
}

function getUserByID($pdo, $userId)
{
    $stmt = $pdo->prepare('SELECT user_id, username, date_added FROM user_passwords WHERE user_id = ?');
    $stmt->execute([$userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function insertNewUser($pdo, $username, $passwordHash)
{
    $check = $pdo->prepare('SELECT user_id FROM user_passwords WHERE username = ?');
    $check->execute([$username]);
    if ($check->fetch()) {
        $_SESSION['message'] = 'That username is already registered.';
        return false;
    }

    $stmt = $pdo->prepare('INSERT INTO user_passwords (username, password) VALUES (?, ?)');
    $stmt->execute([$username, $passwordHash]);
    return true;
}

function loginUser($pdo, $username, $password)
{
    $stmt = $pdo->prepare('SELECT user_id, username, password FROM user_passwords WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $_SESSION['message'] = 'Username or password is invalid.';
        return false;
    }

    $storedPassword = (string) $user['password'];
    $passwordMatches = password_verify($password, $storedPassword);
    $legacyPassword = preg_match('/^[a-f0-9]{40}$/i', $storedPassword) === 1;
    if (!$passwordMatches && $legacyPassword && hash_equals(strtolower($storedPassword), sha1($password))) {
        $passwordMatches = true;
        $upgrade = $pdo->prepare('UPDATE user_passwords SET password = ? WHERE user_id = ?');
        $upgrade->execute([password_hash($password, PASSWORD_DEFAULT), $user['user_id']]);
    }

    if (!$passwordMatches) {
        $_SESSION['message'] = 'Username or password is invalid.';
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    return true;
}
