<?php
require_once("config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Получение списка консультаций пользователя
$stmt = $conn->prepare("
    SELECT r.id, r.data, s.timeSession 
    FROM record r 
    JOIN sessionTime s ON r.time_id = s.id
    WHERE r.user_id = ? 
    ORDER BY r.data DESC
    LIMIT 10
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$records = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои консультации</title>
    <link rel="stylesheet" href="style/user_page.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once "header.php"; ?>        

        <main>
            <h1>Мои консультации</h1>
            <?php if (!empty($records)): ?>
                <table>
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Время</th>
                    </tr>
                    <?php foreach ($records as $index => $record): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($record['data']) ?></td>
                            <td><?= htmlspecialchars($record['timeSession']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>У вас пока нет записей на консультации.</p>
            <?php endif; ?>

            <p><a href="user_page.php">Вернуться в профиль</a></p>
        </main>

        <?php require_once "footer.php"; ?>
    </div>
</body>

</html>