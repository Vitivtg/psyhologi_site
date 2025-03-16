<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {        
        header("Location:login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $webinar_id = $_POST['webinar_id'];

    // Проверяем, не купил ли пользователь уже этот вебинар
    $check_stmt = $conn->prepare("SELECT id FROM webinar_purchases WHERE user_id = ? AND webinar_id = ?");
    $check_stmt->bind_param("ii", $user_id, $webinar_id);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        die("Ошибка: Вы уже купили этот вебинар.");
    }
    $check_stmt->close();

    // Добавляем покупку в базу
    $stmt = $conn->prepare("INSERT INTO webinar_purchases (user_id, webinar_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $webinar_id);

    if ($stmt->execute()) {
        header("Location:webinar.php");
    } else {
        echo "Ошибка при покупке вебинара: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
