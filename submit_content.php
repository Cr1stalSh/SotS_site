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

// Проверка, что пользователь авторизован и имеет права на изменение контента
if (!isset($_SESSION['username']) || ($_SESSION['username'] !== 'yaroslav' && $_SESSION['username'] !== 'admin')) {
    $_SESSION['cont_error-message'] = 'У вас нет прав для выполнения этой операции';
    header('Location: index.php');
    exit();
}

// Проверка, что данные были отправлены через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = filter_input(INPUT_POST, 'postId', FILTER_VALIDATE_INT);
    $postText = filter_input(INPUT_POST, 'postText', FILTER_SANITIZE_STRING);
    $postImage = filter_input(INPUT_POST, 'postImage', FILTER_SANITIZE_STRING);

    try {
        // Проверка существования поста в базе данных
        $stmt = $pdo->prepare('SELECT * FROM content_blocks WHERE id = :id');
        $stmt->execute([':id' => $postId]);
        $block = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($block) {
            // Обновление записи в базе данных
            $updateStmt = $pdo->prepare('UPDATE content_blocks SET author = :author, text = :text, image_url = :image_url WHERE id = :id');
            $updateStmt->execute([
                ':author' => $_SESSION['username'],
                ':text' => $postText ?: $block['text'], // Оставляем старое значение, если новое не указано
                ':image_url' => $postImage ?: $block['image_url'],
                ':id' => $postId
            ]);

            $_SESSION['cont_success-message'] = 'Пост успешно обновлен';
        } else {
            // Создание новой записи в базе данных
            $insertStmt = $pdo->prepare('INSERT INTO content_blocks (id, author, text, image_url) VALUES (:id, :author, :text, :image_url)');
            $insertStmt->execute([
                ':author' => $_SESSION['username'],
                ':id' => $postId,
                ':text' => $postText,
                ':image_url' => $postImage
            ]);

            $_SESSION['cont_success-message'] = 'Новый пост успешно создан';
        }

        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        $_SESSION['cont_error-message'] = 'Ошибка при обработке запроса: ' . $e->getMessage();
        header('Location: index.php');
        exit();
    }
} else {
    $_SESSION['cont_error-message'] = 'Некорректный метод запроса';
    header('Location: index.php');
    exit();
}
?>