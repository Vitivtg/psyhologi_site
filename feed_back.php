<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обратная связь</title>
    <style>
        <?php
        require_once("style/feed_back.css");
        ?>
    </style>
</head>
<body>

    <?php
     require_once "header.php";
    ?>

    <div class="container">
        <h2>Свяжитесь с нами</h2>

        <?php if (isset($_SESSION["message_success"])): ?>
            <p style="color: green;"><strong><?= $_SESSION["message_success"]; unset($_SESSION["message_success"]); ?></strong></p>
        <?php endif; ?>

        <form action="send_message.php" method="POST">
            <label name="name">Ваше имя:</label>
            <input type="text" name="name" required>

            <label name="email">Ваш Email:</label>
            <input type="email" name="email" required>

            <label name="message">Сообщение:</label>
            <textarea name="message" rows="5" required></textarea>

            <button type="submit">Отправить</button>
        </form>
    </div>

    <?php require_once "footer.php"; ?>

</body>
</html>
