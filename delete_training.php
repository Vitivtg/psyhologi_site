<?php
require_once("config.php");


if (!isset($_SESSION["user_role"]) || $_SESSION["user_role"] != 1) {
    die("Доступ запрещен.");
}


if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    die("Некорректный ID.");
}

$id = intval($_GET["id"]);


$stmt = $conn->prepare("DELETE FROM trainings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();


header("Location: trainings.php");
exit();
?>
