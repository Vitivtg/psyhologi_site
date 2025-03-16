<?php
require_once "config.php";

if (isset($_POST['id']) && isset($_POST['block'])) {
    $id = intval($_POST['id']);
    $block = intval($_POST['block']);
    
    $stmt = $conn->prepare("UPDATE users SET block = ? WHERE id = ?");
    $stmt->bind_param("ii", $block, $id);
    if ($stmt->execute()) {
        header("Location: client.php?id=$id");
        exit;
    } else {
        echo "Ошибка обновления статуса.";
    }
} else {
    echo "Некорректные данные.";
}
?>
