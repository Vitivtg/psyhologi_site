<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($user_role != 1) {
        die("Ошибка: У вас нет прав для удаления вебинаров.");
    }

    if (!isset($_POST["name"])) {
        die("Ошибка: Не передано имя вебинара.");
    }

    $name = $_POST["name"];

    
    $stmt = $conn->prepare("SELECT video_file FROM webinars WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $stmt->bind_result($video_file);
    $stmt->fetch();
    $stmt->close();

    if (!$video_file) {
        die("Ошибка: Вебинар не найден.");
    }

    
    $stmt = $conn->prepare("DELETE FROM webinars WHERE name = ?");
    $stmt->bind_param("s", $name);
    
    if ($stmt->execute()) {
        
        $video_path = "video/" . $video_file;
        if (file_exists($video_path)) {
            unlink($video_path);
        }
        header("Location: webinar.php");
    } else {
        echo "Ошибка при удалении: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
