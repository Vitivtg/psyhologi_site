<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Календарь консультаций</title>
    <style>
        <?php require_once "style/consultation.css"; ?>    
    </style>
</head>
<body>

    <?php require_once "header.php"; ?>

    <div class="content">        

        <h3>Выберите дату и время консультации</h3>
        <div class="calendar">
            <div class="calendar-header">
                <button id="prev-month"><<</button>
                <span id="calendar-header"></span>
                <button id="next-month">>></button>
            </div>
            <div class="calendar-days" id="calendar-days"></div>
        </div>

        <div class="select_time">
            <label for="time-select">Выберите время:</label>
            <select id="time-select">
                <option value="">Выберите время</option>
            </select>
        </div>

        <button type="button" class="record_consultation">Записаться на консультацию</button>
    </div>

    <?php
        if (isset($_SESSION["success_message"])) {
            echo "<div class='success'>" . $_SESSION["success_message"] . "</div>";
            unset($_SESSION["success_message"]);
        }
        if (isset($_SESSION["error_message"])) {
            echo "<div class='error'>";
                        echo "<p>" . htmlspecialchars($_SESSION["error_message"]) . "</p>";
                        unset($_SESSION["error_message"]);
                        echo "</div>";            
            unset($_SESSION["error_message"]);
        }
        ?>

    <?php require_once "footer.php"; ?>

    <script>
        let selectedDate = null;
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        const monthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];

        const renderCalendar = () => {
            const daysContainer = document.getElementById('calendar-days');
            const header = document.getElementById('calendar-header');
            header.textContent = `${monthNames[currentMonth]} ${currentYear}`;

            const firstDay = new Date(currentYear, currentMonth, 1).getDay();
            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const prevDays = (firstDay === 0 ? 6 : firstDay - 1);

            daysContainer.innerHTML = '';

            // Добавляем пустые ячейки
            for (let i = 0; i < prevDays; i++) {
                daysContainer.innerHTML += '<div class="day"></div>';
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const todayDate = today.getDate();
            const todayMonth = today.getMonth();
            const todayYear = today.getFullYear();

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentYear, currentMonth, day);
                const formattedDate = date.toLocaleDateString('fr-CA');
                const isToday = day === todayDate && currentMonth === todayMonth && currentYear === todayYear;
                // Позволяем выбирать сегодня, даже если время уже прошло
                const isPastDate = (date < today && !isToday);
                const isSelected = selectedDate && selectedDate === formattedDate;
                const dayClass = `${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''} ${isPastDate ? 'inactive' : ''}`;
                daysContainer.innerHTML += `<div class="day ${dayClass}" data-date="${formattedDate}">${day}</div>`;
            }
        };

        renderCalendar();

        document.getElementById('calendar-days').addEventListener('click', (event) => {
            if (event.target.classList.contains('day') && !event.target.classList.contains('inactive')) {
                const newSelectedDate = event.target.dataset.date;
                if (selectedDate !== newSelectedDate) {
                    selectedDate = newSelectedDate;
                    renderCalendar();
                    loadAvailableTimes(selectedDate);
                }
            }
        });

        const loadAvailableTimes = (selectedDate) => {
            fetch(`get_sessiontime.php?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    const timeSelect = document.getElementById('time-select');
                    timeSelect.innerHTML = ''; // Очищаем предыдущие данные
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Выберите время';
                    timeSelect.appendChild(defaultOption);

                    data.forEach(session => {
                        const option = document.createElement('option');
                        option.value = session;
                        option.textContent = session;
                        timeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Ошибка загрузки данных:', error);
                    alert('Ошибка загрузки данных.');
                });
        };

        document.getElementById('prev-month').addEventListener('click', () => {
            if (currentMonth === 0) {
                currentMonth = 11;
                currentYear--;
            } else {
                currentMonth--;
            }
            renderCalendar();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            if (currentMonth === 11) {
                currentMonth = 0;
                currentYear++;
            } else {
                currentMonth++;
            }
            renderCalendar();
        });

        // Обработчик нажатия на кнопку "Записаться на консультацию"
        document.querySelector('.record_consultation').addEventListener('click', () => {
            if (!selectedDate) {
                alert("Пожалуйста, выберите дату консультации.");
                return;
            }
            const timeSelect = document.getElementById('time-select');
            const selectedTime = timeSelect.value;
            if (!selectedTime) {
                alert("Пожалуйста, выберите время консультации.");
                return;
            }

            // данные для отправки
            const formData = new FormData();
            formData.append("date", selectedDate);
            formData.append("time", selectedTime);

            
            fetch("record_consultation.php", {
                method: "POST",
                body: formData
            })
            .then(response => {
                window.location.href = "consultation.php"; // Перенаправляем обратно на страницу                
            })
            .catch(error => {
                console.error("Ошибка при записи данных:", error);
                alert("Ошибка при записи данных.");
            });
        });
    </script>

</body>
</html>
