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
    <title>Лабиринт</title>
    <link rel="stylesheet" href="styles_index.css">
    <link rel="stylesheet" href="styles_labyrinth.css">
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

    <main>
        <h1>Лабиринт</h1>
        <div class="instructions">
            Пройдите лабиринт<br>Используйте стрелки на клавиатуре для управления
        </div>
        <div class="input-container">
        <input type="number" id="Input" class="input-field" placeholder="Введите размер лабиринта">
        
        <button class="build-button" onclick="restartMaze()">Построить</button>
    </div>
        <div class="maze-container">
            <canvas id="mazeCanvas"></canvas>
        </div>
    </main>

    <div id="MessageModal" class="message_modal">
      <div class="message_modal-content">
      <div class="modal_mes">Поздравляю! Вы прошли лабиринт</div>
      <span class="close" onclick="closeMessage()">Ок</span>
      </div>
    </div>

    <div class="overlay" id="overlay"></div>
    <script src="script_index.js"></script>
    <script src="script_maze_game.js"></script>
</html>