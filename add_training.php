<?php
require_once("config.php");

$success_message = "";
$error_message = "";

// Если форма отправлена
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);

    // Проверка заполненности полей
    if (empty($name) || empty($description)) {
        $error_message = "Заполните все поля!";
    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $error_message = "Ошибка загрузки изображения!";
    } else {
        // Загрузка изображения
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . time() . "_" . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Разрешенные форматы
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_types)) {
            $error_message = "Разрешены только файлы JPG, JPEG, PNG и GIF!";
        } elseif (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Добавление данных в базу
            $stmt = $conn->prepare("INSERT INTO trainings (name, images, description) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $target_file, $description);
            if ($stmt->execute()) {
                $success_message = "Тренинг успешно добавлен!";
            } else {
                $error_message = "Ошибка при добавлении в базу данных!";
            }
            $stmt->close();
        } else {
            $error_message = "Ошибка загрузки изображения!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить тренинг</title>
    <link rel="stylesheet" href="style/add_training.css">
</head>
<body>

    <div class="container">
        <h1>Добавить новый тренинг</h1>

        <?php if ($success_message): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="add_training.php" method="POST" enctype="multipart/form-data">
            <label for="name">Название тренинга:</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Описание:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="image">Изображение:</label>
            <input type="file" name="image" id="image" accept="image/*" required>

            <button type="submit">Добавить</button>
        </form>

        <p><a href="trainings.php">Вернуться к списку тренингов</a></p>
    </div>

</body>
</html>
