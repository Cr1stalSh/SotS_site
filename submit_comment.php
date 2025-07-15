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

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['username'])) {
    $_SESSION['error_message'] = "Вы должны войти в систему, чтобы оставить комментарий";
    header("Location: reg.php");
    exit();
}

// Функция для проверки содержания комментария
function isProfanity($comment) {
    // Разбиваем комментарий на слова
    $words = preg_split('/\s+/', $comment);
    foreach ($words as $word) {
        if (strlen($word) < 3) continue;

        if (checkRepeatingCharacters($word)) {
            return true;
        }
        if (checkAlternatingPattern($word)) {
            return true;
        }
        if (checkNoisyCharacters($word)) {
            return true;
        }
        if (banword($comment)) {
            return true;
        }
    }
    return false;
}

function checkRepeatingCharacters($word) {
    $count = [];
    $length = mb_strlen($word);

    // Подсчет повторяющихся символов
    for ($i = 0; $i < $length; $i++) {
        $char = mb_substr($word, $i, 1); // Получаем символ UTF-8
        if (isset($count[$char])) {
            $count[$char]++;
        } else {
            $count[$char] = 1;
        }
    }

    $maxCount = max($count);
    return ($maxCount / $length > 0.5);
}

function checkAlternatingPattern($word) {
    $chars = preg_split('//u', $word, -1, PREG_SPLIT_NO_EMPTY);
    $length = count($chars);

    if ($length <= 4) return false;

    // Проверка чередующегося шаблона
    for ($i = 2; $i < $length; $i++) {
        if ($chars[$i] !== $chars[$i - 2]) {
            return false;
        }
    }
    return true;
}

function checkNoisyCharacters($word) {
    $letters = preg_replace('/[^\p{L}]/u', '', $word);
    return (mb_strlen($letters) / mb_strlen($word) < 0.7);
}

function banword($comment) {
    $bannedWords = ['анан', 'бан']; 
    $words = preg_split('/\s+/', mb_strtolower($comment));
    return count(array_intersect($words, $bannedWords)) > 0;
}



// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $author = $_SESSION['username'];
    $text = trim($_POST['comment']);

    if (isProfanity($text)) {
    $_SESSION['comm_error_message'] = "Комментарий содержит запрещенные выражения";
    
    try {
        // Проверяем, есть ли пользователь в таблице bad_comments_users
        $stmt = $pdo->prepare("SELECT count FROM bad_comments_users WHERE username = :username");
        $stmt->execute([':username' => $author]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Если пользователь уже есть в таблице, увеличиваем счетчик
            $stmt = $pdo->prepare("UPDATE bad_comments_users SET count = count + 1 WHERE username = :username");
            $stmt->execute([':username' => $author]);
        } else {
            // Если пользователя нет в таблице, добавляем его
            $stmt = $pdo->prepare("INSERT INTO bad_comments_users (username, count) VALUES (:username, 1)");
            $stmt->execute([':username' => $author]);
        }
    } catch (Throwable $e) {
        $_SESSION['comm_error_message'] = "Ошибка записи в таблицу плохих комментариев: " . $e->getMessage();
    }
    header("Location: comments.php");
    exit();
}

    if (!empty($text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO comments (author, text) VALUES (:author, :text)");
            $stmt->execute([
                ':author' => $author,
                ':text' => $text
            ]);
            $_SESSION['comm_success_message'] = "Комментарий успешно добавлен";
        } catch (PDOException $e) {
            $_SESSION['comm_error_message'] = "Ошибка добавления комментария: " . $e->getMessage();
        }
    } else {
        $_SESSION['comm_error_message'] = "Комментарий не может быть пустым.";
    }
    header("Location: comments.php");
    exit();
}
?>
