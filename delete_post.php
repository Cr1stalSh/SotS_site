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

// Проверка, что пользователь авторизован
if (!isset($_SESSION['username']) || ($_SESSION['username'] !== 'yaroslav' && $_SESSION['username'] !== 'admin')) {
    $_SESSION['cont_error-message'] = 'У вас нет прав для выполнения этой операции';
    header('Location: index.php');
    exit();
}

// Проверка, что данные отправлены через POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $deletePostId = filter_input(INPUT_POST, 'deletePostId', FILTER_VALIDATE_INT);

    if ($deletePostId) {
        try {
            // Удаление записи из базы данных
            $stmt = $pdo->prepare('DELETE FROM content_blocks WHERE id = :id');
            $stmt->execute([':id' => $deletePostId]);

            if ($stmt->rowCount() > 0) {
                $_SESSION['cont_success-message'] = 'Пост успешно удален';
            } else {
                $_SESSION['cont_error-message'] = 'Пост с указанным ID не найден';
            }
        } catch (PDOException $e) {
            $_SESSION['cont_error-message'] = 'Ошибка при удалении поста: ' . $e->getMessage();
        }
    } else {
        $_SESSION['cont_error-message'] = 'Некорректный ID поста';
    }
} else {
    $_SESSION['cont_error-message'] = 'Некорректный метод запроса';
}

header('Location: index.php');
exit();
?>
