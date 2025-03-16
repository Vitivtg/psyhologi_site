<?php
require_once("config.php"); 



$sql = "SELECT id, name, images FROM trainings";
$result = $conn->query($sql);

$is_admin = isset($_SESSION["user_role"]) && $_SESSION["user_role"] == 1;
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тренинги</title>
    <link rel="stylesheet" href="style/trainings.css">
</head>

<body>
    <?php require_once "header.php"; ?>

    <div class="container">
        <h1>Тренинги</h1>

        <!-- "Добавить тренинг"-->
        <!--<?php if ($is_admin): ?>
            <a href="add_training.php" class="add-btn">➕ Добавить тренинг</a>
        <?php endif; ?>-->

        <div class="trainings-list">
            <?php 
            $index = 0;
            while ($row = $result->fetch_assoc()): 
            ?>
                <div class="training-card hidden <?= $index % 2 === 0 ? 'left' : 'right' ?>">
                    <a href="training_details.php?id=<?= $row['id'] ?>">
                        <div class="overlay"><?= htmlspecialchars($row['name']) ?></div>
                        <img src="<?= htmlspecialchars($row['images']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                    </a>

                    <!-- "Редактировать" и "Удалить" -->
                    <?php if ($is_admin): ?>
                        <div class="admin-buttons">
                            <a href="edit_training.php?id=<?= $row['id'] ?>" class="edit-btn">✏️ Редактировать</a>
                            <a href="delete_training.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Вы уверены, что хотите удалить этот тренинг?');">❌ Удалить</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php 
                $index++; 
            endwhile; 
            ?>
        </div>
    </div>

    <?php require_once "footer.php"; ?>

    <script>
        function revealCards() {
            const cards = document.querySelectorAll('.training-card.hidden');
            const triggerPoint = window.innerHeight * 0.85;

            cards.forEach(card => {
                const cardTop = card.getBoundingClientRect().top;
                if (cardTop < triggerPoint) {
                    card.classList.remove('hidden');
                }
            });
        }

        window.addEventListener('scroll', revealCards);
        window.addEventListener('load', revealCards);
    </script>
</body>

</html>
