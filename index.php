<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">
    
    <title>Майя Иванова</title>
    <style>
        <?php
        include "style/index.css"
        ?>
    </style>
</head>

<body>
    <div class="wrapper">
        <?php
        require_once "header.php";
        ?>

        <div class="content">
            <div><img class="headPhoto" src="./image/logo.png" alt="logo"></div>
            <div class="text">
                <div class="letter">
                    <h3>Уважаемые друзья!</h3>
                    <p>Рада приветствовать вас на моём сайте. Я искренне верю, что основой любой успешной организации является личность — человек, который сумел понять и раскрыть свои сильные стороны, глубоко осознал свои ценности и цели, и объединил вокруг себя единомышленников.
                        Мой опыт как кандидата психологических наук и организационного психолога убедил меня в том, что успех компании напрямую зависит от внутренней силы её лидеров и сотрудников. Индивидуальное развитие каждого человека неизбежно приводит к процветанию всего коллектива.
                        Именно поэтому я предлагаю как индивидуальные консультации, направленные на раскрытие личностного потенциала, так и организационные тренинги, позволяющие наладить эффективное взаимодействие в командах.
                        Кроме того, я провожу индивидуальные консультации, не связанные с организационной психологией, оказывая поддержку и сопровождение людям в ситуациях личностных кризисов, профессионального выгорания и других сложных жизненных периодов.
                        Я буду рад помочь вам и вашим организациям совершить качественный скачок, начав с самого главного — с развития личности, которое становится фундаментом общего успеха.
                    </p>
                    <p>С уважением и верой в ваш потенциал, Иванова Майя.</p>
                </div>
            </div>
            <div class="information">
                <img class="img_info" src="./image/info.png" alt="info">
                <p>Психологическое сопровождение руководителей</p>
                <p>Программы по развитию персонала</p>
                <p>Социально-психологические тренинги</p>
                <p>Индивидуальные консультации</p>
                <p>Психологическая диагностика</p>
                <p>Психологическое просвещение</p>
            </div>
        </div>

        <?php
        require_once "footer.php";
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const contentBlocks = document.querySelectorAll(".content > div");

            contentBlocks.forEach((block, index) => {
                block.style.opacity = "0"; // Скрываем блоки изначально
                block.style.transform = "translateY(20px)";
                block.style.transition = "opacity 1s ease-out, transform 1s ease-out";
            });

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.style.opacity = "1";
                            entry.target.style.transform = "translateY(0)";
                        }, index * 600); // Добавляем задержку для эффекта последовательности
                    }
                });
            }, {
                threshold: 0.2
            });

            contentBlocks.forEach(block => observer.observe(block));
        });
    </script>

</body>
</html>