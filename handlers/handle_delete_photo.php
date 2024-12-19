<?php

require_once('../db.php');

// Проверка за логнат потребител
if (!isset($_SESSION['username'])) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'You must be logged in to delete photos.';
    header('Location: ../index.php?page=login');
    exit;
}

$username = $_SESSION['username'];
$imagepath = $_POST['imagepath'] ?? '';

// Проверка дали е подаден валиден път към снимка
if (empty($imagepath)) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Invalid photo selected for deletion.';
    header('Location: ../index.php?page=myphotos');
    exit;
}

// Вземане на потребителското ID
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

// Проверка дали снимката принадлежи на потребителя
$query = "SELECT * FROM photos WHERE imagepath = ? AND user_id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$imagepath, $userId]);
$image = $stmt->fetch();

if (!$image) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'You do not have permission to delete this photo.';
    header('Location: ../index.php?page=myphotos');
    exit;
}

if ($image['user_id'] != $userId) {
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'You do not have permission to delete this photo.';
    header('Location: ../index.php?page=myphotos');
    exit;
}

// Премахване на записа от базата данни
$query = "DELETE FROM photos WHERE id =? ";
$stmt = $pdo->prepare($query);

if (!$stmt->execute([$image['id']])) {
    // Грешка при изтриване
    $_SESSION['flash']['message']['type'] = 'danger';
    $_SESSION['flash']['message']['text'] = 'Error deleting photo. Please try again.';
    header('Location: ../index.php?page=myphotos');
    exit;
}

// Изтриване на файла от директорията
$fileToDelete = "../uploads/" . $imagepath;
if (file_exists($fileToDelete)) {
    unlink($fileToDelete);
}

$_SESSION['flash']['message']['type'] = 'success';
$_SESSION['flash']['message']['text'] = 'Photo deleted successfully.';
header('Location: ../index.php?page=myphotos');
exit;
?>
