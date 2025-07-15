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

if (!isset($_SESSION['username']) || !($_SESSION['username'] === 'yaroslav' || $_SESSION['username'] === 'admin')) {
    // Если пользователь не авторизован или не имеет прав
    $_SESSION['error_message'] = "У вас нет прав для удаления комментариев.";
    header("Location: comments.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
    $commentId = intval($_POST['comment_id']);

    try {
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :id");
        $stmt->execute([':id' => $commentId]);

        $_SESSION['comm_success_message'] = "Комментарий успешно удален";
    } catch (PDOException $e) {
        $_SESSION['comm_error_message'] = "Ошибка удаления: " . $e->getMessage();
    }
}

header("Location: comments.php");
exit();
?>
