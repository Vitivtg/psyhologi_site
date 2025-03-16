<?php
require_once("config.php");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Обновление телефона
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["phone"])) {
        $new_phone = trim($_POST["phone"]);

        // Обновляем телефон
        $stmt = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
        $stmt->bind_param("si", $new_phone, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION["success"] = "Телефон успешно обновлен!";
        header("Location: user_page.php");
        exit();
    }

    // Обновление пароля
    if (!empty($_POST["password"]) && !empty($_POST["password_confirm"])) {
        $password = $_POST["password"];
        $password_confirm = $_POST["password_confirm"];

        // Проверка пароля
        if (!preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
            $_SESSION["error_password"] = "Пароль должен содержать минимум 8 символов, 1 заглавную букву и 1 цифру.";
            header("Location: user_page.php");
            exit();
        }

        // Проверка на совпадение паролей
        if ($password !== $password_confirm) {
            $_SESSION["error_password"] = "Пароли не совпадают!";
            header("Location: user_page.php");
            exit();
        }

        // Хешируем новый пароль
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Обновляем пароль в БД
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
        $stmt->close();

        $_SESSION["success"] = "Пароль успешно обновлен!";
        header("Location: user_page.php");
        exit();
    }
}

// Получение данных пользователя
$stmt = $conn->prepare("SELECT firstname, lastname, phone, email, gender, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo "Пользователь не найден.";
    exit;
}

// Определяем роль пользователя
$user_role = $user["role"];

// Показывать ли раздел "Мои консультации" (только для обычных пользователей)
$show_consultations = isset($_GET["show"]) && $_GET["show"] === "consultations" && $user_role == 0;

if ($show_consultations) {
    // Получение списка консультаций пользователя с реальным временем
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
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="style/user_page.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once "header.php"; ?>

        <main>
            <h1>Профиль пользователя</h1>
            <?php if (isset($_SESSION["success"])): ?>
                <p style="color: green;"><strong><?= $_SESSION["success"];
                                                    unset($_SESSION["success"]); ?></strong></p>
            <?php endif; ?>

            <div class="conteiner">
                <div class="user"><strong>Имя:</strong> </div>
                <div class="user"><?= htmlspecialchars($user['firstname']) ?></div>
                <div class="user"><strong>Фамилия:</strong></div>
                <div class="user"><?= htmlspecialchars($user['lastname'] ?? 'Не указано') ?></div>
                <div class="user"><strong>Email:</strong></div>
                <div class="user"><?= htmlspecialchars($user['email']) ?></div>
                <div class="user"><strong>Пол:</strong></div>
                <div class="user"><?= htmlspecialchars($user['gender']) ?></div>
            </div>

            <!-- Форма для редактирования телефона -->
            <form method="POST">
                <div class="phone"><strong>Телефон:</strong></div>
                <div><input type="text" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required></div>
                <button type="submit">Сохранить</button>
            </form>

            <!-- Форма для смены пароля -->
            <form method="POST">
                <div class="pass">
                    <div><label><strong>Новый пароль:</strong></label></div>
                    <div><input type="password" name="password" required></div>
                    <div><label><strong>Повторите пароль:</strong></label></div>
                    <div><input type="password" name="password_confirm" required></div>
                </div>
                <div><button type="submit">Обновить пароль</button></div>

                <?php if (isset($_SESSION["error_password"])): ?>
                    <p style="color: red;"><strong><?= $_SESSION["error_password"];
                    unset($_SESSION["error_password"]); ?></strong></p>
                <?php endif; ?>
            </form>

            <!-- Отображение ссылок в зависимости от роли -->
            <?php if ($user_role == 0): ?>
    <a href="my_consultations.php" class="btn user-btn">Мои консультации</a>
<?php elseif ($user_role == 1): ?>
    <a href="add_webinar.php" class="btn admin-btn">Добавить вебинар</a>
    <a href="add_training.php" class="btn admin-btn">Добавить тренинг</a>
<?php endif; ?>

        </main>

        <?php require_once "footer.php"; ?>
    </div>
</body>

</html>