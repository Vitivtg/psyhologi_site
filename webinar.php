<?php
require 'config.php';

// Получаем список вебинаров
$sql = "SELECT id, name, video_file, description FROM webinars";
$result = $conn->query($sql);

// Проверяем, купил ли текущий пользователь вебинар
$purchased_webinars = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $purchase_stmt = $conn->prepare("SELECT webinar_id FROM webinar_purchases WHERE user_id = ?");
    $purchase_stmt->bind_param("i", $user_id);
    $purchase_stmt->execute();
    $purchase_result = $purchase_stmt->get_result();
    
    while ($row = $purchase_result->fetch_assoc()) {
        $purchased_webinars[] = $row['webinar_id'];
    }
    $purchase_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вебинары</title>
    <style>
        <?php include "style/webinar.css"; ?>        
    </style>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const videos = document.querySelectorAll("video");
            videos.forEach(video => {
                video.setAttribute("controlsList", "nodownload");
                video.oncontextmenu = function() {
                    return false;
                };
            });
        });
    </script>   
</head>
<body>
    <?php include "header.php"; ?>

    <div class="container">
        <h1>Список вебинаров</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($webinar = $result->fetch_assoc()): ?>
                <div class="webinar">
                    <h2><?= htmlspecialchars($webinar['name']) ?></h2>
                    <p><?= nl2br(htmlspecialchars($webinar['description'])) ?></p>

                    <div class="video-container">
                        <?php if ($user_role == 1 || in_array($webinar['id'], $purchased_webinars)): ?>
                            <!-- Если админ или вебинар куплен, показываем нормальное видео -->
                            <video controls controlsList="nodownload">
                                <source src="video/<?= htmlspecialchars($webinar['video_file']) ?>" type="video/mp4">
                                Ваш браузер не поддерживает видео.
                            </video>
                        <?php else: ?>
                            <!-- Если вебинар не куплен, делаем видео заблокированным -->
                            <video class="locked-video">
                                <source src="video/<?= htmlspecialchars($webinar['video_file']) ?>" type="video/mp4">
                                Ваш браузер не поддерживает видео.
                            </video>
                            <div class="buy-overlay">Купите вебинар, чтобы смотреть</div>
                        <?php endif; ?>
                    </div>

                    <?php if ($user_role == 0 && !in_array($webinar['id'], $purchased_webinars)): ?>
                        <!-- Кнопка "Купить" для обычных пользователей -->
                        <form action="buy_webinar.php" method="POST">
                            <input type="hidden" name="webinar_id" value="<?= $webinar['id'] ?>">
                            <button type="submit" class="btn btn-buy">Купить</button>
                        </form>
                    <?php endif; ?>

                    <?php if ($user_role == 1): ?>
                        <!-- Кнопки администратора -->
                        <div class="admin-buttons">
                            <form action="delete_webinar.php" method="POST" style="display:inline;">
                                <input type="hidden" name="name" value="<?= htmlspecialchars($webinar['name']) ?>">
                                <button type="submit" class="btn btn-delete">Удалить</button>
                            </form>
                            <a href="edit_webinar.php?name=<?= urlencode($webinar['name']) ?>" class="btn btn-edit">Редактировать</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Нет доступных вебинаров.</p>
        <?php endif; ?>
    </div>

    <?php require_once "footer.php"; ?>
</body>
</html>
