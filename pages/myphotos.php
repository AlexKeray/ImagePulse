<?php
// Страница продукти
$images = [];
$user = $_SESSION['username'] ?? '';
$stmt = $pdo->prepare("
        SELECT photos.id, photos.imagepath, users.username
        FROM photos
        INNER JOIN users ON photos.user_id = users.id
        WHERE users.username = :search
        ORDER BY photos.id DESC
    ");
    $stmt->execute(['search' => $user]);

// Извличане на резултатите
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-4">
    <h3>Want to post a photo?</h3>
    <form action="./handlers/handle_upload.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>

<div class="row">
    <?php
    if (count($images) === 0) {
        echo '<h1>No images found</h1>';
    } else {
        foreach ($images as $image): ?>
            <div class="col-md-4 mb-4">
                <style>
                .card {
                    background-color: #212529; /* Същият тъмен фон като навигацията */
                    color: white; /* Бял текст */
                    border: none; /* Без рамка */
                    border-radius: 10px; /* Заоблени ъгли */
                }

                .card img {
                    border-top-left-radius: 10px; /* Заобляне на горните ъгли на изображението */
                    border-top-right-radius: 10px;
                }

                .card p {
                    margin: 0; /* Премахване на излишно разстояние около текста */
                }
                </style>
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($image['imagepath']); ?>" class="card-img-top" alt="Photo">
                    <div class="card-body">
                        <p class="card-text">Uploaded by: <strong><?php echo htmlspecialchars($image['username']); ?></strong></p>
                        <form method="POST" action="./handlers/handle_delete_photo.php">
                            <input type="hidden" name="imagepath" value="<?php echo htmlspecialchars($image['imagepath']); ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach;
    }
    ?>
</div>