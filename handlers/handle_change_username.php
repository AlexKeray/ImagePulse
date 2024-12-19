<?php

require_once('../db.php');
session_start();

$error = '';
foreach ($_POST as $key => $value) {
    if (empty($value)) {
        $error = 'Enter all fields!';
        break;
    }
}

if (mb_strlen($error) > 0) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = $error;
    $_SESSION['flash']['data'] = $_POST;
    header('Location: ../index.php?page=account');
    exit;
} else {
    $username = $_POST['username'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $old_username = $_SESSION['username'] ?? '';

    if (mb_strlen($old_username) == 0) {
        $error = 'User not logged in!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        session_unset();
        session_destroy();

        header('Location: ../index.php?page=login');
        exit;
    }

    // Проверка дали текущата парола е вярна
    $query = "SELECT password FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$old_username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current_password, $user['password'])) {
        $error = 'Incorrect current password!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        $_SESSION['flash']['data'] = $_POST;
        header('Location: ../index.php?page=account');
        exit;
    }

    // Проверка дали новото потребителско име вече съществува
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $existing_user = $stmt->fetch();

    if ($existing_user) {
        $error = 'Username already taken!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        $_SESSION['flash']['data'] = $_POST;
        header('Location: ../index.php?page=account');
        exit;
    }

    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$old_username]);
    $user_id = $stmt->fetch();

    // Обновяване на потребителското име
    $query = "UPDATE users SET username = :username WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $params = [
        ':username' => $username,
        ':id' => $user_id['id']
    ];
    if ($stmt->execute($params)) {
        $_SESSION['flash']['message']['type'] = 'success';
        $_SESSION['flash']['message']['text'] = "Username successfully changed!";
        $_SESSION['username'] = $username;
        header('Location: ../index.php?page=home');
        exit;
    } else {
        $error = 'Error changing username!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        $_SESSION['flash']['data'] = $_POST;
        header('Location: ../index.php?page=account');
        exit;
    }
}
?>
