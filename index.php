<?php
    $page = $_GET['page'] ?? 'home';

    require_once('db.php');

    $flash = $_SESSION['flash'] ?? null;
    
    // Изтрива флаш съобщението след показване
    unset($_SESSION['flash']);

    if (!isset($_SESSION['username'])) {
        $allowed_pages = ['home', 'login', 'register']; // Страници, достъпни без логин
        if (!in_array($page, $allowed_pages)) {
            header('Location: index.php?page=login'); // Пренасочване към login с правилния URL
            exit;
        }
    }

?>

<?php if ($flash): ?>
    <div class="alert alert-<?php echo htmlspecialchars($flash['message']['type']); ?> text-center" role="alert">
        <?php echo htmlspecialchars($flash['message']['text']); ?>
    </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ImagePulse</title>
    <!-- Bootstrap 5.3 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        input {
            background-color: #212529 !important; /* Сив фон */
            color: white !important; /* Бял текст */
            border: 1px solid #495057 !important; /* Граница */
            border-radius: 8px !important; /* Заоблени ъгли */
            padding: 0.5rem !important;
        }

        input::placeholder {
            color: #ffffff !important; /* Бял текст за плейсхолдъра */
            opacity: 0.7 !important; /* Леко прозрачност */
        }

        input:focus {
            background-color: #212529 !important; /* Запазване на сивия фон при фокус */
            border-color: #6c757d !important; /* Промяна на границата при фокус */
            box-shadow: none !important; /* Премахване на синята сянка на Bootstrap */
        }
        body {
        background-color: #121212; /* Тъмен сив цвят */
        color: #ffffff; /* Светъл текст за четимост */
        }
        .navbar {
        display: flex;
        align-items: center; /* Центрира всички елементи във височина */
        }
        .navbar-toggler {
            margin: auto 0; /* Центрира бутона вертикално */
            align-self: center; /* Центрира бутона вертикално */
        }
        /* Общо стилизиране за всички input полета */
        .btn-primary {
        background-color: #495057; /* Сив бутон */
        border-color: #495057;
        }

        .btn-primary:hover {
            background-color: #6c757d; /* По-светъл сив при hover */
            border-color: #6c757d;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="?page=home">ImagePulse</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link <?php echo($page == 'feed' ? 'active' : '') ?>" aria-current="page" href="?page=feed">Feed</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo($page == 'myphotos' ? 'active' : '') ?>" href="?page=myphotos">My Photos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo($page == 'account' ? 'account' : '') ?>" href="?page=account">Account</a>
                        </li>
                    </ul>
                    <style>
                        .form-logout {
                            margin: 0 auto; /* Центрира формата хоризонтално */
                        }
                        .button-logout {
                            display: block; /* Гарантира, че бутонът заема пълния блок */
                            margin: auto; /* Центрира го във формата */
                        }
                        .form-change-container {
                            padding: 0px;
                        }
                    </style>
                    <div class="d-flex flex-row align-items-center gap-4">
                        <?php
                            if (isset($_SESSION['username'])) {
                                echo '<span class="text-light me-3">Hi, ' . htmlspecialchars($_SESSION['username']) . '</span>';
                                echo '
                                    <form method="POST" class="form-logout" action="./handlers/handle_logout.php">
                                        <button type="submit" class="btn btn-outline-light button-logout">Log out</button>
                                    </form>
                                ';
                            } else {
                                echo '<a href="?page=login" class="btn btn-outline-light">Log in</a>';
                                echo '<a href="?page=register" class="btn btn-outline-light">Sign up</a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4" style="min-height:80vh;">
        <?php
            if (file_exists('./pages/' . $page . '.php')) {
                require_once('./pages/' . $page . '.php');
            } else {
                require_once('./pages/not_found.php');
            }
        ?>
    </main>
    <footer class="bg-dark text-center py-5 mt-auto">
        <div class="container">
            <span class="text-light">© 2024 All rights reserved</span>
        </div>
    </footer>
</body>
</html>