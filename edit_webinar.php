<?php
require 'config.php';

if ($user_role != 1) {
    die("Ошибка: У вас нет прав для редактирования вебинаров.");
}

if (!isset($_GET["name"])) {
    die("Ошибка: Не передано имя вебинара.");
}

$name = $_GET["name"];

// Получаем данные о вебинаре
$stmt = $conn->prepare("SELECT description FROM webinars WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->bind_result($description);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Редактирование вебинара</title>
    <style>
        <?php
        include "style/edit_webinar.css"
        ?>
    </style>

</head>

<body>
    <h2>Редактирование вебинара</h2>
    <form action="update_webinar.php" method="POST">
        <div>
            <input type="hidden" name="old_name" value="<?= htmlspecialchars($name) ?>">

            <label>Новое название:</label>
            <input type="text" name="new_name" value="<?= htmlspecialchars($name) ?>" required>
        </div>

        <div>
            <label>Описание:</label>
            <textarea name="description" required><?= htmlspecialchars($description) ?></textarea>
        </div>

        <button type="submit">Сохранить</button>
    </form>
</body>

</html>