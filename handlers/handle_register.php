<?php

require_once('../db.php');

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
    header('Location: ../index.php?page=register');
    exit;
} else {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Проверка дали има потребител с такова име
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        $error = 'Registration error!';
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = $error;
        $_SESSION['flash']['data'] = $_POST;
        header('Location: ../index.php?page=register');
        exit;
    }

    $hash = password_hash($password, PASSWORD_ARGON2I);

        $query = "INSERT INTO users (username, `password`) VALUES (:username, :hash)";
        $stmt = $pdo->prepare($query);
        $params = [
            ':username' => $username,
            ':hash' => $hash
        ];

        if ($stmt->execute($params)) {
            $_SESSION['flash']['message']['type'] = 'success';
            $_SESSION['flash']['message']['text'] = "Successfull registration!";
            header('Location: ../index.php?page=login');
            exit;
        } else {
            $error = 'Registration error!';
            $_SESSION['flash']['message']['type'] = 'danger';
            $_SESSION['flash']['message']['text'] = $error;
            $_SESSION['flash']['data'] = $_POST;
            header('Location: ../index.php?page=register');
            exit;
        }
}

?>