<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список записей</title>
    <style>
        <?php include "style/list_entries.css"; ?>    
    </style>
</head>
<body>

    <?php
     require_once "header.php";
     ?>

    <div class="content">
        <h3>Выберите дату чтобы посмотреть записи</h3>
        <div class="calendar">
            <div class="calendar-header">
                <button id="prev-month"><<</button>
                <span id="calendar-header"></span>
                <button id="next-month">>></button>
            </div>
            <div class="calendar-days" id="calendar-days"></div>
        </div>        
    </div>    

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
                daysContainer.innerHTML += '<div class="day empty"></div>';
            }

            const today = new Date();
            today.setHours(0, 0, 0, 0);

            for (let day = 1; day <= daysInMonth; day++) {
                const date = new Date(currentYear, currentMonth, day);
                const formattedDate = date.toISOString().split('T')[0];
                const isToday = today.toDateString() === date.toDateString();
                const isSelected = selectedDate && selectedDate === formattedDate;

                const dayClass = `${isToday ? 'today' : ''} ${isSelected ? 'selected' : ''}`;
                daysContainer.innerHTML += `<div class="day ${dayClass}" data-date="${formattedDate}">${day}</div>`;
            }
        };

        renderCalendar();

        document.getElementById('calendar-days').addEventListener('click', (event) => {
            if (event.target.classList.contains('day')) {
                const newSelectedDate = event.target.dataset.date;
                if (selectedDate !== newSelectedDate) {
                    selectedDate = newSelectedDate;
                    renderCalendar();
                    loadAvailableRecords(selectedDate);
                }
            }
        });

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

        const loadAvailableRecords = (selectedDate) => {
            fetch(`fetch_records.php?date=${selectedDate}`)
                .then(response => response.json())
                .then(data => {
                    const recordsContainer = document.getElementById('records-container');
                    if (data.length > 0) {
                        let tableHTML = `<table border="1" cellpadding="5">
                                            <tr>
                                                <th>№</th>
                                                <th>Фамилия</th>
                                                <th>Имя</th>
                                                <th>Время консультации</th>
                                            </tr>`;
                        data.forEach((record, index) => {
                            tableHTML += `<tr>
                                            <td>${index + 1}</td>
                                            <td>${record.lastname}</td>
                                            <td>${record.firstname}</td>
                                            <td>${record.timeSession}</td>
                                          </tr>`;
                        });
                        tableHTML += `</table>`;
                        recordsContainer.innerHTML = tableHTML;
                    } else {
                        recordsContainer.innerHTML = "<p>На выбранную дату записей нет.</p>";
                    }
                })
                .catch(error => console.error("Ошибка загрузки записей:", error));
        };

        
        document.addEventListener("DOMContentLoaded", function() {
            const contentDiv = document.querySelector(".content");
            const recordsDiv = document.createElement("div");
            recordsDiv.id = "records-container";
            contentDiv.appendChild(recordsDiv);
        });
    </script>

</body>
</html>
