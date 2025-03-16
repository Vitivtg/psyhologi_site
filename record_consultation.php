<?php
session_start();
require_once "config.php";

// Проверка авторизации пользователя
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error_message"] = "Вам нужно войти в систему для записи на консультацию.";    
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedDate = $_POST["date"] ?? null;
    $selectedTime = $_POST["time"] ?? null;

    if (!$selectedDate || !$selectedTime) {
        $_SESSION["error_message"] = "Необходимо выбрать дату и время консультации.";
        header("Location: consultation.php");
        exit;
    }

    // Проверка доступного времени в sessionTime
    $stmt = $conn->prepare("SELECT id FROM sessionTime WHERE timeSession = ? AND timeFree = 1");
    if (!$stmt) {
        $_SESSION["error_message"] = "Ошибка подготовки запроса: " . $conn->error;
        header("Location: consultation.php");
        exit;
    }
    $stmt->bind_param("s", $selectedTime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $time_id = $row["id"];
    } else {
        $_SESSION["error_message"] = "Выбранное время недоступно.";
        session_write_close();
        header("Location: consultation.php");
        exit;
    }
    $stmt->close();

    $user_id = $_SESSION["user_id"];

    // Проверяем, нет ли уже такой записи
    $stmt = $conn->prepare("SELECT id FROM record WHERE data = ? AND user_id = ? AND time_id = ?");
    if ($stmt) {
        $stmt->bind_param("sii", $selectedDate, $user_id, $time_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            $_SESSION["error_message"] = "Вы уже записаны на эту дату и время.";
            header("Location: consultation.php");
            exit;
        }
        $stmt->close();
    }

    // Вставляем новую запись
    $stmt = $conn->prepare("INSERT INTO record (data, user_id, time_id) VALUES (?, ?, ?)");
    if (!$stmt) {
        $_SESSION["error_message"] = "Ошибка подготовки запроса: " . $conn->error;
        session_write_close();
        header("Location: consultation.php");
        exit;
    }
    $stmt->bind_param("sii", $selectedDate, $user_id, $time_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION["success_message"] = "Консультация успешно записана.";            
        } else {
            $_SESSION["error_message"] = "Запись не была добавлена, проверьте данные.";
        }
    } else {
        $_SESSION["error_message"] = "Ошибка выполнения запроса: " . $stmt->error;
    }

    $stmt->close();
    session_write_close();
    header("Location: consultation.php");
    exit;
} else {
    $_SESSION["error_message"] = "Некорректный запрос.";
    header("Location: consultation.php");
    exit;
}

?>
