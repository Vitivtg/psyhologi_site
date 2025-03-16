<?php
require_once "config.php";

// Получаем параметры сортировки
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id'; // По умолчанию сортировка по ID
$order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'DESC' : 'ASC';

// Разрешенные столбцы для сортировки
$allowed_sort_columns = ['firstname', 'lastname'];
if (!in_array($sort, $allowed_sort_columns)) {
    $sort = 'id';
}

// Получаем параметры поиска
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_by = isset($_GET['search_by']) && in_array($_GET['search_by'], ['firstname', 'lastname']) ? $_GET['search_by'] : 'lastname';

// Создание SQL-запроса с учетом поиска
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE $search_by LIKE ? ORDER BY $sort $order");
    $search_param = "%$search%";
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY $sort $order");
}

$stmt->execute();
$result = $stmt->get_result();

// Определяем новое направление сортировки
$new_order = ($order === 'ASC') ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients</title>
    <style>
        <?php include "style/clients.css"; ?>
    </style>
</head>

<body>
    <div class="wrapper">
        <?php require_once "header.php"; ?>

        <h1>Список клиентов</h1>

        <!-- Форма поиска -->
        <form method="GET" action="">
            <label type="name">Поиск</label>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Введите имя или фамилию">
            <select name="search_by">
                <option value="firstname" <?php echo ($search_by === 'firstname') ? 'selected' : ''; ?>>По имени</option>
                <option value="lastname" <?php echo ($search_by === 'lastname') ? 'selected' : ''; ?>>По фамилии</option>
            </select>
            <button type="submit">Поиск</button>
        </form>

        <div class="clients">
            <table cellspacing="0" cellpadding="10">
                <thead>
                    <tr>
                        <th>№</th>
                        <th><a href="?sort=firstname&order=<?php echo $new_order; ?>&search=<?php echo urlencode($search); ?>&search_by=<?php echo $search_by; ?>">Имя</a></th>
                        <th><a href="?sort=lastname&order=<?php echo $new_order; ?>&search=<?php echo urlencode($search); ?>&search_by=<?php echo $search_by; ?>">Фамилия</a></th>
                        <th>Пол</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Информация</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$count}</td>
                            <td>{$row['firstname']}</td>
                            <td>{$row['lastname']}</td>
                            <td>{$row['gender']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['phone']}</td>                            
                            <td><a href='client.php?id={$row['id']}'>подробнее</a></td>
                        </tr>";
                        $count++;
                    }
                    $stmt->close();
                    ?>
                </tbody>
            </table>
        </div>

        <?php require_once "footer.php"; ?>
    </div>
</body>

</html>