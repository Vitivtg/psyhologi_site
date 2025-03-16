<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($user_role != 1) {
        die("Ошибка: У вас нет прав для редактирования вебинаров.");
    }

    $old_name = $_POST["old_name"];
    $new_name = $_POST["new_name"];
    $description = $_POST["description"];

    $stmt = $conn->prepare("UPDATE webinars SET name = ?, description = ? WHERE name = ?");
    $stmt->bind_param("sss", $new_name, $description, $old_name);

    if ($stmt->execute()) {
        header("Location: webinar.php");
    } else {
        echo "Ошибка при обновлении: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
