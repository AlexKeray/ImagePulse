<?php

require_once('../db.php');

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$login_error = false;

if (empty($username) || empty($password)) {
    $login_error = true;
} else {
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user) {
        $login_error = true;
    } else {
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['userid'] = $user['id'];
        } else {
            $login_error = true;
        }
    }
}

if ($login_error) {
    header('Location: ../index.php?page=login&error');
    $_SESSION['flash']['data']['username'] = $username;
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Login credentials are wrong!';
    exit;
}

if (isset($_SESSION['username'])) {
    header('Location: ../index.php');
    exit;
} else {
    header('Location: ../index.php?page=login&error');
    exit;
}