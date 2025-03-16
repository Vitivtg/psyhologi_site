<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lora:wght@400;500;600&display=swap" rel="stylesheet">

    <title>Обо мне</title>
    <style>
        <?php
        include("style/about.css");
        ?>
    </style>
</head>

<body>
    <div class="wrapper">
        <?php require_once "header.php"; ?>

        <div class="about-container">
            <img src="image/about.jpg" alt="Мой портрет">
            <div class="about-text">
                <p>В 2007 году окончила Одесский национальный университет имени И.И. Мечникова, факультет психологии, кафедру социальной и организационной психологии.</p>
                <p>Получила полное высшее образование по специальности «Психология» (уровень-специалитет) и приобрела квалификацию психолога, преподавателя психологии (диплом с отличием).</p>
                <p>В 2019 году защитила кандидатскую диссертацию по специальности: 19.00.01 общая психология, история психологии, в Одесском национальном университете имени И.И. Мечникова.</p>
                <p>Более 15 лет опыта работы...</p>
                <ul>
                    <li>Автор и ведущий программ обучения и развития персонала в организациях</li>
                    <li>Автор и ведущий программ обучения и развития компетенций в области психологии менеджмента</li>
                    <li>Автор и ведущий тренингов по психологии</li>
                    <li>Автор более 50-ти статей и пособий</li>
                    <li>Психодиагностика персонала и отбор кадров</li>
                    <li>Индивидуальное консультирование и коучинг руководителей</li>
                    <li>Сопровождение внедрения инноваций в организациях</li>
                </ul>
            </div>
        </div>

        <?php require_once "footer.php"; ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const elements = document.querySelectorAll(".about-text p, .about-text li");

            function typeText(element, text, callback) {
                let i = 0;
                element.innerHTML = "";
                element.style.opacity = "1";

                function type() {
                    if (i < text.length) {
                        element.innerHTML += text.charAt(i);
                        i++;
                        setTimeout(type, 10);
                    } else {
                        if (callback) setTimeout(callback, 500);
                    }
                }

                type();
            }

            function showNext(index) {
                if (index < elements.length) {
                    const text = elements[index].textContent.trim();
                    elements[index].style.opacity = "1";
                    typeText(elements[index], text, () => showNext(index + 1));
                }
            }

            showNext(0);
        });
    </script>


</body>

</html>