<?php
// Страница продукти
$images = [];
$search = $_GET['search'] ?? '';

// Проверка дали има въведено търсене
if (mb_strlen($search) > 0) {
    // Търсене на потребители и техните снимки
    $stmt = $pdo->prepare("
        SELECT photos.id, photos.imagepath, users.username
        FROM photos
        INNER JOIN users ON photos.user_id = users.id
        WHERE LOWER(users.username) LIKE LOWER(:search)
        ORDER BY photos.id DESC
    ");
    $stmt->execute(['search' => '%' . strtolower($search) . '%']);
} else {
    // Ако няма търсене, извеждаме всички снимки
    $stmt = $pdo->query("
        SELECT photos.id, photos.imagepath, users.username
        FROM photos
        INNER JOIN users ON photos.user_id = users.id
        ORDER BY photos.id DESC
    ");
}

// Извличане на резултатите
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
.reaction-buttons {
    display: flex;
    gap: 10px;
}

.like-button, .dislike-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #6c757d; /* Сив по подразбиране */
    background-color: #212529; /* Сив фон */
    cursor: pointer;
    transition: all 0.3s ease;
}

.like-button.active, .dislike-button.active {
    border-color: #ffffff; /* Бял контур при активиране */
    background-color: #495057; /* Тъмен фон */
}

.like-button::before {
    content: '👍';
    color: #6c757d; /* Сив цвят */
    font-size: 1.2rem;
}

.like-button.active::before {
    color: #ffffff; /* Бял цвят при активиране */
}

.dislike-button::before {
    content: '👎';
    color: #6c757d;
    font-size: 1.2rem;
}

.dislike-button.active::before {
    color: #ffffff;
}


</style>

<div class="row">
    <form class="mb-4" method="GET">
        <div class="input-group">
            <input type="hidden" name="page" value="feed">
            <input type="text" class="form-control" placeholder="Search user" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary search-button" type="submit">Search</button>
        </div>
    </form>
</div>

<div class="row">
    <?php if (!empty($search)): ?>
        <h5>Searching: <strong><?php echo htmlspecialchars($search); ?></strong></h5>
    <?php endif; ?>
</div>

<div class="row">
    <?php
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_SESSION['username']]);
        $user = $stmt->fetch();

    if (count($images) === 0) {
        echo '<h1>No images found</h1>';
    } else {
        foreach ($images as $image): 
            $query = "SELECT `like` FROM likes WHERE photo_id = ? AND user_id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$image['id'], $user['id']]);
            $likeStatus = $stmt->fetch();
            $isLiked = $likeStatus && $likeStatus['like'] == 1;
            $isDisliked = $likeStatus && $likeStatus['like'] == -1;
        ?>
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
                .btn-success {
                    background-color: #28a745;
                    border-color: #28a745;
                    color: white;
                }

                .btn-outline-success {
                    background-color: transparent;
                    border-color: #28a745;
                    color: #28a745;
                }

                .btn-danger {
                    background-color: #dc3545;
                    border-color: #dc3545;
                    color: white;
                }

                .btn-outline-danger {
                    background-color: transparent;
                    border-color: #dc3545;
                    color: #dc3545;

                }

                .like-dislike-buttons {
                    display: flex; /* Подрежда елементите в един ред */
                    align-items: center; /* Центрира вертикално */
                    justify-content: space-between; /* Разделя текста и бутоните */
                    width: 100%; /* Задължително за `space-between` */
                }

                .like-dislike-buttons div {
                    display: flex; /* Подрежда бутоните един до друг */
                    gap: 0.5rem; /* Разстояние между бутоните */
                }

                .like-dislike-buttons p {
                    margin: 0; /* Премахва излишното разстояние на текста */
                    padding-right: 1rem; /* Добавя малко разстояние между текста и бутоните */
                }

                .like-dislike-buttons .btn {
                    flex-shrink: 0; /* Предотвратява разтягането на бутоните */
                }




                </style>
                <div class="card">
                    <img src="uploads/<?php echo htmlspecialchars($image['imagepath']); ?>" class="card-img-top" alt="Photo">
                    <div class="card-body">
                        
                        <!-- Бутон Like -->
                         <div class="like-dislike-buttons  ms-auto">
                            <p class="card-text">Uploaded by: <strong><?php echo htmlspecialchars($image['username']); ?></strong></p>
                            <div>
                            <button class="btn like-btn <?php echo $isLiked ? 'btn-success' : 'btn-outline-success'; ?>" 
                                data-photo-id="<?php echo $image['id']; ?>" 
                                data-action="like">Like</button>
                            
                            <!-- Бутон Dislike -->
                            <button class="btn dislike-btn <?php echo $isDisliked ? 'btn-danger' : 'btn-outline-danger'; ?>" 
                                data-photo-id="<?php echo $image['id']; ?>" 
                                data-action="dislike">Dislike</button>
                            </div>
                         </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
    }
    ?>
</div>

<script>
    console.log("JavaScript loaded!");

    $(document).ready(function () {
    $(".like-btn, .dislike-btn").click(function () {
        let button = $(this);
        let photoId = button.data("photo-id");
        let action = button.data("action");

        console.log("Button clicked! Photo ID: " + photoId + ", Action: " + action);

        $.ajax({
    url: '/imagePulse/handlers/handle_like_dislike.php',
    type: 'POST',
    data: {
        photo_id: photoId,
        action: action
    },
    beforeSend: function () {
        console.log('Sending AJAX request...');
    },
    success: function (response) {
        console.log("Response received:", response);
        if (response.success) {
            if (action === 'like') {
                button.toggleClass('btn-outline-success btn-success');
                button.siblings(".dislike-btn").removeClass('btn-danger').addClass('btn-outline-danger');
            } else if (action === 'dislike') {
                button.toggleClass('btn-outline-danger btn-danger');
                button.siblings(".like-btn").removeClass('btn-success').addClass('btn-outline-success');
            }
        } else {
            alert('Error: ' + response.message);
        }
    },
    error: function (xhr, status, error) {
        console.error('AJAX Error:', xhr, status, error);
        alert('AJAX Error: ' + error);
    }
});

    });
});

</script>
