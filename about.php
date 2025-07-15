<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Beiruti:wght@200..900&family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&display=swap" rel="stylesheet">
    <title>О игре</title>
    <link rel="stylesheet" href="styles_index.css">
    <link rel="stylesheet" href="styles_about.css">
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

    // Получение данных для слайдов
    $stmt = $pdo->query("SELECT * FROM info_slides ORDER BY id ASC");
    $slides = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="info-container">
        <button class="nav-btn prev-btn" onclick="navigate(-1)">&#8592;</button>
        <div class="info-content">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="info-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                <h2 class="info-title"><?= htmlspecialchars($slide['title']); ?></h2>
                <p class="info-text"><?= nl2br(htmlspecialchars($slide['text'])); ?></p>
                <img src="<?= htmlspecialchars($slide['image_url']); ?>" alt="Изображение" class="info-image">
            </div>
        <?php endforeach; ?>
        </div>
        <button class="nav-btn next-btn" onclick="navigate(1)">&#8594;</button>
    </div>
</body>

    <div class="overlay" id="overlay"></div>
    <script src="script_index.js"></script>
    <script src="script_about.js"></script>
</html>