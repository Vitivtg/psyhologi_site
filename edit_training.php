<?php
require_once("config.php");


if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != 1) {
    die("Доступ запрещен.");
}


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Некорректный ID.");
}

$id = intval($_GET["id"]);

// Обработка обновления тренинга
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);

    if (empty($name) || empty($description)) {
        $error = "Заполните все поля.";
    } else {
        $stmt = $conn->prepare("UPDATE trainings SET name = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $description, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: trainings.php");
        exit();
    }
}


$stmt = $conn->prepare("SELECT name, description FROM trainings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$training = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование тренинга</title>
    <link rel="stylesheet" href="style/edit_training.css">
</head>
<body>
    <div class="container">
        <h1>Редактирование тренинга</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="name">Название:</label>
            <input type="text" name="name" id="name" value="<?= htmlspecialchars($training["name"]) ?>" required>

            <label for="description">Описание:</label>
            <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($training["description"]) ?></textarea>

            <button type="submit">Сохранить</button>
        </form>

        <a href="trainings.php" class="back-btn">Назад</a>
    </div>
</body>
</html>
