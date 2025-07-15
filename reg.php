<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Beiruti:wght@200..900&family=Edu+AU+VIC+WA+NT+Hand:wght@400..700&display=swap" rel="stylesheet">
    <title>Shadows of the Soul — Вход и Регистрация</title>
    <link rel="stylesheet" href="styles_reg.css">
</head>
<body>
<img src="pictures/reg.png" alt="Background Image" class="background-image">
<div class="gradient-overlay"></div>
    <div class="auth-container">
        <div class="form-box">
            <div class="form-header">
                <button id="login-tab" class="active">Вход</button>
                <button id="register-tab">Регистрация</button>
            </div>
            <?php
                if (isset($_SESSION['success-message'])) {
                echo "<div class='success-message'>" . htmlspecialchars($_SESSION['success-message']) . "</div>";
                unset($_SESSION['success-message']);
                }
            ?>
            <?php
                if (isset($_SESSION['error_message'])) {
                echo "<div class='error-message'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
                unset($_SESSION['error_message']);
                }
            ?>
            <form id="login-form" class="auth-form" method="POST" action="auth.php">
                <h2>Вход</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="hidden" name="action" value="login">
                <button type="submit" class="btn">Войти</button>
            </form>

            <form id="register-form" class="auth-form hidden" method="POST" action="auth.php">
                <h2>Регистрация</h2>
                <input type="text" name="username" placeholder="Имя пользователя" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="password" name="password_confirm" placeholder="Повторите пароль" required>
                <input type="hidden" name="action" value="register">
                <button type="submit" class="btn">Зарегистрироваться</button>
            </form>

        </div>
    </div>

    <script src="script_reg.js"></script>
</body>
</html>
