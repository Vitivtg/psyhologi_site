<?php
session_start();  

    try {
        $conn=new mysqli("localhost", "root", "", "site_db");
        if($conn->connect_error){
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
    }
    catch(Exception $e)
    {
        $_SESSION["error_message"]="Connection not established";
        header("Location:register.php");
        die();
    } 
    $conn->set_charset("utf8"); 
    
    if (!isset($_SESSION['user_id'])) {
        $user_role = 0; 
    } else {
        
        $user_id = $_SESSION['user_id'];
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($user_role);
        $stmt->fetch();
        $stmt->close();
    }
