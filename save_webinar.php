<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];

    // Директория для загрузки видео
    $target_dir = "video/";
    $video_file = basename($_FILES["video_file"]["name"]);
    $target_file = $target_dir . $video_file;
    
    // Проверка типа файла (разрешен только MP4)
    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($file_type != "mp4") {
        die("Ошибка: можно загружать только MP4 файлы.");
    }

    // Перемещение загруженного файла в папку
    if (move_uploaded_file($_FILES["video_file"]["tmp_name"], $target_file)) {
        // Запрос на вставку данных в базу
        $sql = "INSERT INTO webinars (name, video_file, description) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $video_file, $description);

        if ($stmt->execute()) {
            header("Location: add_webinar.php");
        } else {
            echo "Ошибка при добавлении вебинара: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "Ошибка загрузки видео.";
    }

    $conn->close();
}
?>
