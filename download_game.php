<?php
session_start();

$logFile = 'download_log.txt';
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = __DIR__ . '/' . $file;
    function logDownload($fileName, $ipAddress) {
        global $logFile;
        $currentDateTime = date('Y-m-d H:i:s');
        $logEntry = "{$fileName};{$currentDateTime};{$ipAddress};успешно\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    function logunDownload($fileName, $ipAddress) {
        global $logFile;
        $currentDateTime = date('Y-m-d H:i:s');
        $logEntry = "{$fileName};{$currentDateTime};{$ipAddress};ошибка\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    if (file_exists($filePath)) {
        $clientIP = $_SERVER['REMOTE_ADDR'];
        logDownload($file, $clientIP);
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename="' . basename($file) . '"');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         header('Pragma: public');
         header('Content-Length: ' . filesize($filePath));
         readfile($filePath);
        exit;
    } else {
        echo "<script>alert('Файл не найден.');</script>";
        $clientIP = $_SERVER['REMOTE_ADDR'];
        logunDownload($file, $clientIP);
    }
} 
?>