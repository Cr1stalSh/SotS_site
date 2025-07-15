<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Комментарии</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Beiruti:wght@200..900&family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_comments.css">
    <link rel="stylesheet" href="styles_index.css">
</head>
<body>
<header class="header">
        <div class="logo-im">
        <img src="pictures/dreamc.png" alt="Icon" class="icon">
       </div>
        <div class="game-title">
            <span>Shadows of the Soul</span>
        </div>
        <div class="header-buttons">
             <?php if (isset($_SESSION['username'])): ?>
                <span class="username"><?= htmlspecialchars($_SESSION['username']); ?></span>
                 <form method="POST" action="logout.php">
                  <button class="logout-btn" id="logoutButton" name="logout" type="submit">Выйти</button>
                 </form>
             <?php else: ?>
              <button class="login-btn" id="loginButton">Войти</button>
             <?php endif; ?> 
            <button class="menu-btn">☰</button>
        </div>
    </header>
    <div class="side-panel" id="sidePanel">
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="about.php">О игре</a></li>
                <li><a href="labyrinth.php">Лабиринт</a></li>
                <li><a href="comments.php">Комментарии</a></li>
            </ul>
        </nav>
    </div>

<?php
// Подключение к базе данных
$host = 'localhost';
$dbname = 'kurs';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получение комментариев
$stmt = $pdo->query("SELECT * FROM comments ORDER BY timestamp DESC");
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="comments-section">
    <h1>Комментарии</h1>

    <form class="comment-form" method="POST" action="submit_comment.php">
            <textarea name="comment" placeholder="Напишите комментарий..." required></textarea>
            <button type="submit" class="btn">Отправить</button>
    </form>

    <?php if (isset($_SESSION['comm_success_message'])): ?>
        <div class="comm_success-message"><?= htmlspecialchars($_SESSION['comm_success_message']); ?></div>
        <?php unset($_SESSION['comm_success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['comm_error_message'])): ?>
        <div class="comm_error-message"><?= htmlspecialchars($_SESSION['comm_error_message']); ?></div>
        <?php unset($_SESSION['comm_error_message']); ?>
    <?php endif; ?>

    <div class="comments-container">
    <?php foreach ($comments as $comment): ?>
        <div class="comment">
            <span class="author"><?= htmlspecialchars($comment['author']); ?></span>
            <p class="text"><?= htmlspecialchars($comment['text']); ?></p>
            <span class="timestamp"><?= htmlspecialchars($comment['timestamp']); ?></span>
            
            <?php if (isset($_SESSION['username']) && ($_SESSION['username'] === 'yaroslav' || $_SESSION['username'] === 'admin')): ?>
                <!-- Кнопка удаления -->
                <form method="POST" action="delete_comment.php" class="delete-form">
                    <input type="hidden" name="comment_id" value="<?= htmlspecialchars($comment['id']); ?>">
                    <button type="submit" class="delete-btn">Удалить</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>

</main>
<div class="overlay" id="overlay"></div>    
</body>
<script src="script_index.js"></script>
</html>
