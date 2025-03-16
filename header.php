<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="style/header.css">
</head>

<body>
    <header>
        <!-- Кнопка гамбургер-меню -->
        <div class="hamburger-menu">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <nav class="head">
            
                <li><a href="index.php">Главная</a></li>
                <li><a href="about.php">Обо мне</a></li>
                <li><a href="webinar.php">Вебинары</a></li>
                <li><a href="trainings.php">Тренинги</a></li>
                <li><a href="consultation.php">Консультации</a></li>
                <li class="my_list">
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <?php if (isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1): ?>
                            <a href="clients.php">Мои клиенты</a>
                            <a href="list_entries.php">Список консультаций</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </li>
                <li class="last_li">
                    <?php if (isset($_SESSION["user_name"])): ?>
                        <p>Привет, <a href="user_page.php?id=<?= $_SESSION['user_id'] ?>">
                                <?= htmlspecialchars($_SESSION["user_name"]) ?>
                            </a></p>
                        <a href="logout.php">Выйти</a>
                    <?php else: ?>
                        <a href="login.php">Войти</a>
                    <?php endif; ?>
                </li>            
        </nav>
    </header>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const hamburger = document.querySelector(".hamburger-menu");
        const nav = document.querySelector(".head");

        hamburger.addEventListener("click", function () {
            nav.classList.toggle("active");
            hamburger.classList.toggle("open");
        });
    });
</script>

</body>
</html>