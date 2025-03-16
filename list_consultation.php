<?php
require_once "config.php";

if (!isset($_GET['user_id'])) {
    die("Некорректный запрос.");
}

$user_id = intval($_GET['user_id']);

// Запрос к базе данных для получения консультаций пользователя
$stmt = $conn->prepare("
    SELECT r.id, u.firstname, u.lastname, r.data, s.timeSession 
    FROM record r
    JOIN users u ON r.user_id = u.id
    JOIN sessionTime s ON r.time_id = s.id
    WHERE r.user_id = ?
    ORDER BY r.data ASC, s.timeSession ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список консультаций</title>
    <style>
        <?php include "style/client.css"; ?>
    </style>
</head>
<body>

    <?php require_once "header.php"; ?>

    <h1>Список консультаций</h1>

    <div class="client">
        <table cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Дата</th>
                    <th>Время</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $num = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$num}</td>
                            <td>{$row['firstname']}</td>
                            <td>{$row['lastname']}</td>
                            <td>{$row['data']}</td>
                            <td>{$row['timeSession']}</td>
                          </tr>";
                    $num++;
                }
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <a class="a1" href="clients.php">Назад к списку клиентов</a>

    <?php require_once "footer.php"; ?>

</body>
</html>
