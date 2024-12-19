<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../db.php');


header('Content-Type: application/json');

try {
    // Проверка дали потребителят е логнат
    if (!isset($_SESSION['username'])) {
        throw new Exception('You must be logged in to like or dislike.');
    }

    // Извличане на ID на потребителя чрез username от сесията
    $query = "SELECT id FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch();

    if (!$user) {
        throw new Exception('User not found.');
    }

    $userId = $user['id'];

    // Извличане на параметрите от POST
    $photoId = $_POST['photo_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$photoId || !$action) {
        throw new Exception('Invalid request parameters.');
    }

    // Проверка дали записът вече съществува
    $query = "SELECT * FROM likes WHERE photo_id = ? AND user_id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$photoId, $userId]);
    $likeRecord = $stmt->fetch();

    if ($likeRecord) {
        if (($likeRecord['like'] == 1 && $action == 'like') || ($likeRecord['like'] == -1 && $action == 'dislike')) {
            // Ако потребителят натиска отново същия бутон -> изтриване на записа
            $query = "DELETE FROM likes WHERE photo_id = ? AND user_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$photoId, $userId]);
        } else {
            // Обновяване на записа
            $newValue = ($action === 'like') ? 1 : -1;
            $query = "UPDATE likes SET `like` = ? WHERE photo_id = ? AND user_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$newValue, $photoId, $userId]);
        }
    } else {
        // Добавяне на нов запис
        $newValue = ($action === 'like') ? 1 : -1;
        $query = "INSERT INTO likes (photo_id, user_id, `like`) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$photoId, $userId, $newValue]);
    }

    echo json_encode(['success' => true]);
    exit;

} catch (Exception $e) {
    // Връщане на грешка като JSON отговор
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

?>