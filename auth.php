<?php
session_start();
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

// Определяем действие (регистрация или вход)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'register') {
            registerUser($pdo);
        } elseif ($_POST['action'] === 'login') {
            loginUser($pdo);
        }
    }
}

// Функция регистрации
function registerUser($pdo) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $passwordConfirm = trim($_POST['password_confirm']);

    // Проверка совпадения паролей
    if ($password !== $passwordConfirm) {
        $_SESSION['error_message'] = "Пароли не совпадают";
        header("Location: reg.php");
        exit();
    }

    try {
        // Проверяем, существует ли пользователь с таким же email или именем
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email OR username = :username");
        $stmt->execute([':email' => $email, ':username' => $username]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                $_SESSION['error_message'] = "Этот email уже используется";
            } elseif ($existingUser['username'] === $username) {
                $_SESSION['error_message'] = "Это имя пользователя уже занято";
            }
            header("Location: reg.php");
            exit();
        }

        // Выполняем вставку нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $password
        ]);

        $_SESSION['success-message'] = "Регистрация успешна";
        header("Location: reg.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Ошибка при регистрации: " . $e->getMessage();
        header("Location: reg.php");
        exit();
    }
}


// Функция входа
function loginUser($pdo) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]); // передаем параметр
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { // сравнение строк
            // Успешный вход
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // Перенаправление на главную страницу
            exit();
        } else {
            // Ошибка входа
            $_SESSION['error_message'] = "Неверный email или пароль";
            header("Location: reg.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Ошибка при входе: " . $e->getMessage();
        header("Location: reg.php");
        exit();
    }
}
?>
