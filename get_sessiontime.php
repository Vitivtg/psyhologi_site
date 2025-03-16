<?php
require_once "config.php";

date_default_timezone_set('Europe/Chisinau'); 

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date']; 
    $currentTime = date("H:i"); 

    
    $stmt = $conn->prepare("
        SELECT timeSession FROM sessionTime 
        WHERE timeFree = 1 
        AND timeSession NOT IN (
            SELECT s.timeSession FROM record r
            JOIN sessionTime s ON r.time_id = s.id
            WHERE r.data = ?
        )
    ");
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $availableTimes = [];
    while ($row = $result->fetch_assoc()) {
        $time = $row['timeSession'];

        
        if ($selectedDate > date("Y-m-d") || $time >= $currentTime) {
            $availableTimes[] = $time;
        }
    }

    error_log("Выбранная дата: " . $selectedDate);
error_log("Текущее время: " . $currentTime);
error_log("Доступные записи: " . print_r($availableTimes, true));

    echo json_encode($availableTimes);
}
?>
