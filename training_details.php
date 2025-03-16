<?php
require_once("config.php");

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Некорректный ID");
}

$id = intval($_GET["id"]);

// Получаем данные тренинга
$stmt = $conn->prepare("SELECT name, images, description FROM trainings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$training = $result->fetch_assoc();

if (!$training) {
    die("Тренинг не найден.");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($training["name"]) ?></title>
    <link rel="stylesheet" href="style/training_details.css">
</head>
<body style="background-image: url('<?= htmlspecialchars($training["images"]) ?>');">


    <button class="close-btn" onclick="window.location.href='trainings.php'">&times;</button>

    <div class="content">
        <h1><?= htmlspecialchars($training["name"]) ?></h1>
        <p><?= nl2br(htmlspecialchars($training["description"])) ?></p>
    </div>
    
</body>
</html>
