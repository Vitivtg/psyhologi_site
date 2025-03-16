<?php
require_once "config.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Безопасное преобразование параметра id в целое число


    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("d", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        die("Клиент не найден.");
    }
} else {
    die("Некорректный запрос.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client</title>
    <style>
        <?php
        include "style/client.css"
        ?>
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        require_once "header.php";
        ?>
        <h1>Клиент</h1>
        <div class="client">
            <table cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Пол</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Дата регистрации</th>
                        <th>Список консультаций</th>
                        <th>Блокировка</th>
                        <th>Действие</th>
                    </tr><a href=""></a>
                </thead>
                <tbody>
                    <?php
                    echo "<tr>
                            <td>{$client['firstname']}</td>
                            <td>{$client['lastname']}</td>
                            <td>{$client['gender']}</td>
                            <td>{$client['email']}</td>
                            <td>{$client['phone']}</td>
                            <td>{$client['created_at']}</td>
                            <td><a href='list_consultation.php?user_id={$client['id']}'>посмотреть</a></td>
                            <td>" . ($client['block'] ? 'Заблокирован' : 'Нет') . "</td>
                            <td>
                                <form action='update_status.php' method='POST'>
                                <input type='hidden' name='id' value='{$client['id']}'>
                                <input type='hidden' name='block' value='" . ($client['block'] ? '0' : '1') . "'>
                                <button type='submit'>" . ($client['block'] ? 'Разблокировать' : 'Заблокировать') . "</button>
                                </form>
                            </td>
                    </tr>";
                    ?>
                </tbody>
            </table>
        </div>
        <a class="a1" href="clients.php">назад к списку клиентов</a>

        <?php
        require_once "footer.php";
        ?>
    </div>
</body>

</html>