<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/models.php';

function redirectTo($location)
{
    header('Location: ' . $location);
    exit;
}

if (isset($_POST['registerUserBtn'])) {
    requireGuest('../index.php');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (strlen($username) < 3 || strlen($username) > 50 || strlen($password) < 8) {
        $_SESSION['message'] = 'Use a username between 3 and 50 characters and a password of at least 8 characters.';
        redirectTo('../register.php');
    }

    if (insertNewUser($pdo, $username, password_hash($password, PASSWORD_DEFAULT))) {
        $_SESSION['message'] = 'Account created. You can now log in.';
        redirectTo('../login.php');
    }
    redirectTo('../register.php');
}

if (isset($_POST['loginUserBtn'])) {
    requireGuest('../index.php');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $_SESSION['message'] = 'Enter both your username and password.';
        redirectTo('../login.php');
    }

    if (loginUser($pdo, $username, $password)) {
        redirectTo('../index.php');
    }
    redirectTo('../login.php');
}

if (isset($_POST['logoutUserBtn'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $cookie = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    }
    session_destroy();
    redirectTo('../login.php');
}

requireLogin('../login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}

if (isset($_POST['insertWebDevBtn'])) {
    $success = insertGameDev(
        $pdo,
        trim($_POST['firstName'] ?? ''),
        trim($_POST['lastName'] ?? ''),
        $_POST['dateOfBirth'] ?? '',
        trim($_POST['specialization'] ?? ''),
        $_SESSION['user_id']
    );

    if ($success) {
        redirectTo('../index.php');
    }
    exit('Unable to add game developer.');
}

if (isset($_POST['editGameDevBtn'])) {
    $success = updateGameDev(
        $pdo,
        trim($_POST['firstName'] ?? ''),
        trim($_POST['lastName'] ?? ''),
        $_POST['dateOfBirth'] ?? '',
        trim($_POST['specialization'] ?? ''),
        (int) ($_GET['game_dev_id'] ?? 0),
        $_SESSION['user_id']
    );

    if ($success) {
        redirectTo('../index.php');
    }
    exit('Unable to update game developer.');
}

if (isset($_POST['deleteGameDevBtn'])) {
    $success = deleteGameDev($pdo, (int) ($_GET['game_dev_id'] ?? 0));

    if ($success) {
        redirectTo('../index.php');
    }
    exit('Unable to delete game developer.');
}

if (isset($_POST['insertNewProjectBtn'])) {
    $gameDevId = (int) ($_GET['game_dev_id'] ?? 0);
    $success = insertProject(
        $pdo,
        trim($_POST['projectName'] ?? ''),
        trim($_POST['technologiesUsed'] ?? ''),
        $gameDevId,
        $_SESSION['user_id']
    );

    if ($success) {
        redirectTo('../viewprojects.php?game_dev_id=' . $gameDevId);
    }
    exit('Unable to add project.');
}

if (isset($_POST['editProjectBtn'])) {
    $gameDevId = (int) ($_GET['game_dev_id'] ?? 0);
    $success = updateProject(
        $pdo,
        trim($_POST['projectName'] ?? ''),
        trim($_POST['technologiesUsed'] ?? ''),
        (string) ($_GET['project_id'] ?? ''),
        $_SESSION['user_id']
    );

    if ($success) {
        redirectTo('../viewprojects.php?game_dev_id=' . $gameDevId);
    }
    exit('Unable to update project.');
}

if (isset($_POST['deleteProjectBtn'])) {
    $gameDevId = (int) ($_GET['game_dev_id'] ?? 0);
    $success = deleteProject($pdo, (string) ($_GET['project_id'] ?? ''));

    if ($success) {
        redirectTo('../viewprojects.php?game_dev_id=' . $gameDevId);
    }
    exit('Unable to delete project.');
}

http_response_code(400);
exit('Unknown form submission.');
