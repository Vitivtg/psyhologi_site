<?php
require_once "config.php";

if (isset($_GET['date'])) {
    $selectedDate = $_GET['date'];

    
    $stmt = $conn->prepare("
        SELECT users.lastname, users.firstname, sessionTime.timeSession
        FROM record
        JOIN users ON record.user_id = users.id
        JOIN sessionTime ON record.time_id = sessionTime.id
        WHERE record.data = ?
    ");
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
    
    echo json_encode($records);
    $stmt->close();
    $conn->close();
}
?>
