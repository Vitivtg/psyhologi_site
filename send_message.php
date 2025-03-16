<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION["message_error"] = "Все поля обязательны для заполнения!";
        header("Location: feed_back.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION["message_error"] = "Введите корректный Email!";
        header("Location: feed_back.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $_SESSION["message_success"] = "Ваше сообщение успешно отправлено!";
    } else {
        $_SESSION["message_error"] = "Ошибка при отправке сообщения.";
    }

    $stmt->close();
    $conn->close();

    header("Location: feed_back.php");
    exit();
}
?>
