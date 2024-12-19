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
    header('Location: ../index.php?page=account');
    exit;
} else {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $username = $_SESSION['username'] ?? '';

    if (mb_strlen($username) == 0) {
        $error = 'User not logged in!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        header('Location: ../index.php?page=login');
        exit;
    }

    // Проверка дали старата парола е вярна
    $query = "SELECT password FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!password_verify($old_password, $user['password'])) {
        $error = 'Incorrect old password!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        header('Location: ../index.php?page=account');
        exit;
    }

    // Хеширане на новата парола
    $hash = password_hash($new_password, PASSWORD_ARGON2I);

    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user_id = $stmt->fetch();

    // Обновяване на паролата
    $query = "UPDATE users SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $params = [
        ':password' => $hash,
        ':id' => $user_id['id']
    ];

    if ($stmt->execute($params)) {
        $_SESSION['flash']['message']['type'] = 'success';
        $_SESSION['flash']['message']['text'] = "Password successfully changed!";
        header('Location: ../index.php?page=home');
        exit;
    } else {
        $error = 'Error changing password!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        header('Location: ../index.php?page=account');
        exit;
    }
}
?>
