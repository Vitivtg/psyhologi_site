<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить вебинар</title>
    <style>
        
        <?php
            require_once("style/add_webinar.css")
        ?>
        
    </style>
</head>
<body>
    <div class="container">
        <h2>Добавить новый вебинар</h2>
        <form action="save_webinar.php" method="POST" enctype="multipart/form-data">
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" required>

            <label for="description">Описание:</label>
            <textarea name="description" id="description" required></textarea>

            <label for="video_file">Выберите видеофайл (MP4):</label>
            <input type="file" name="video_file" id="video_file" accept="video/mp4" required>

            <button type="submit">Добавить вебинар</button>
        </form>
        <p><a href="webinar.php">Вернуться к списку вебинаров</a></p>
    </div>
</body>
</html>