<?php

require_once('../db.php');

// Проверка за логнат потребител
if (!isset($_SESSION['username'])) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'You must be logged in to upload photos.';
    header('Location: ../index.php?page=login');
    exit;
}

$username = $_SESSION['username'];

// Проверка за качен файл
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    $fileTmpPath = $_FILES['photo']['tmp_name'];
    $fileName = basename($_FILES['photo']['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

    // Проверка за позволен формат
    if (!in_array($fileExtension, $allowedExtensions)) {
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = 'Invalid file type. Please upload an image.';
        header('Location: ../index.php?page=myphotos');
        exit;
    }

    // Генериране на уникално име за файла
    $newFileName = uniqid() . '.' . $fileExtension;
    $destination = $uploadDir . $newFileName;

    // Преместване на файла в директорията uploads
    if (move_uploaded_file($fileTmpPath, $destination)) {
        // Вземане на потребителското ID от базата данни чрез username
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['flash']['message']['type'] = 'danger';
            $_SESSION['flash']['message']['text'] = 'User not found.';
            header('Location: ../index.php?page=myphotos');
            exit;
        }

        $userId = $user['id'];

        // Запис на информацията за файла в базата данни
        $query = "INSERT INTO photos (imagepath, user_id) VALUES (:imagepath, :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':imagepath' => $newFileName,
            ':user_id' => $userId
        ]);

        $_SESSION['flash']['message']['type'] = 'success';
        $_SESSION['flash']['message']['text'] = 'Photo uploaded successfully!';
        header('Location: ../index.php?page=myphotos');
        exit;
    } else {
        $_SESSION['flash']['message']['type'] = 'danger';
        $_SESSION['flash']['message']['text'] = 'There was an error uploading your photo.';
        header('Location: ../index.php?page=myphotos');
        exit;
    }
} else {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'No file uploaded.';
    header('Location: ../index.php?page=myphotos');
    exit;
}
?>
