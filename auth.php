<?php
require_once __DIR__ . '/dbConfig.php';

function requireLogin($loginPath = 'login.php')
{
    if (empty($_SESSION['user_id']) || empty($_SESSION['username'])) {
        header('Location: ' . $loginPath);
        exit;
    }
}

function requireGuest($indexPath = 'index.php')
{
    if (!empty($_SESSION['user_id'])) {
        header('Location: ' . $indexPath);
        exit;
    }
}
