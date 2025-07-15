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
    <title>Shadows of the Soul</title>
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

    $stmt = $pdo->query("SELECT * FROM content_blocks");
    $contentBlocks = $stmt->fetchAll();
    ?>

    <div class="content">
    <div class="content-container">
    
    <?php foreach ($contentBlocks as $index => $block): ?>
        <div class="content-block">
            <?php if ($index % 2 === 0): ?>
                <!-- Если индекс четный: Сначала текст, потом картинка -->
                <div class="text-box">
                    <?= nl2br(htmlspecialchars($block['text'])); ?>
                </div>
                <div class="image-box">
                    <img src="<?= htmlspecialchars($block['image_url']); ?>" alt="Изображение">
                </div>
            <?php else: ?>
                <!-- Если индекс нечетный: Сначала картинка, потом текст -->
                <div class="image-box">
                    <img src="<?= htmlspecialchars($block['image_url']); ?>" alt="Изображение">
                </div>
                <div class="text-box">
                    <?= nl2br(htmlspecialchars($block['text'])); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
    </div>

<!-- Блок для кнопок -->
<?php if (!isset($_SESSION['username'])): ?>       
    <form method="POST" action="message_download.php">
        <div class="download-section">
        <button class="download-btn" id="downloadBtn">Скачать игру</button>
        </div>
        </form>
        <?php else: ?>
        <div class="download-section">
        <button class="download-btn" onclick="showCaptcha()" id="downloadBtn" >Скачать игру</button>
        <?php if (isset($_SESSION['username']) && ($_SESSION['username'] === 'yaroslav' || $_SESSION['username'] === 'admin')): ?>  
        <button class="content-btn" onclick="showContent()">Изменить блок</button>
        <?php endif; ?> 
        </div>
 <?php endif; ?> 

 <div id="contentModal" class="cont_modal">
      <div class="cont_modal-content">
      <span class="close" onclick="closeContent()">&times;</span>
      <form class="comment-form" method="POST" action="submit_content.php">
        <label for="postId">ID поста:</label>
        <input type="number" id="postId" name="postId" required placeholder="Введите ID поста">
        
        <label for="postText">Текст поста:</label>
        <textarea id="postText" name="postText" rows="4" placeholder="Введите текст поста"></textarea>
        
        <label for="postImage">Картинка:</label>
        <input type="text" id="postImage" name="postImage" placeholder="Введите местоположение картинки">
        
        <button type="submit" class="modal-btn">Подтвердить</button>
    </form>
    <!-- Форма для удаления поста -->
    <form class="delete-form" method="POST" action="delete_post.php">
        <label for="deletePostId">ID поста для удаления:</label>
        <input type="number" id="deletePostId" name="deletePostId" required placeholder="Введите ID поста">
    
        <button type="submit" class="modal-btn delete-btn">Удалить</button>
    </form>
      </div>
    </div>

 <div id="captchaModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeCaptcha()">&times;</span>
        <p>Введите символы, чтобы начать загрузку:</p>
        <p id="captchaText" class="ct"></p>
        <input type="text" id="captchaInput" placeholder="Введите символы">
        <button onclick="validateCaptcha()">Проверить</button>
        <p id="errorMessage" style="color: red;"></p>
      </div>
    </div>
    
    <?php if (isset($_SESSION['cont_success-message'])): ?>
        <div class="cont_success-message"><?= htmlspecialchars($_SESSION['cont_success-message']); ?></div>
        <?php unset($_SESSION['cont_success-message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['cont_error-message'])): ?>
        <div class="cont_error-message"><?= htmlspecialchars($_SESSION['cont_error-message']); ?></div>
        <?php unset($_SESSION['cont_error-message']); ?>
    <?php endif; ?>
    

<div class="overlay" id="overlay"></div>

<script src="script_capcha.js"></script>
<script src="script_index.js"></script>
<script src="script_content.js"></script>

</body>
</html>
