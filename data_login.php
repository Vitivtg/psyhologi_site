<?php
require_once "config.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST["password"]);

    if (empty($email) || empty($password)) {
        $_SESSION["error_message"] = "Все поля обязательны для заполнения!";
        header("Location: login.php");
        exit();
    }

    // Валидация email и пароля
    /*if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match("/^(?=.*[A-Z])(?=.*\d).{8,}$/", $password)) {
        $_SESSION["error_message"] = "Неверный формат email или пароль!";
        header("Location: login.php");
        exit();
    }*/
    
    $stmt = $conn->prepare("SELECT id, firstname, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user["password"])) {            
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $email;
            $_SESSION["user_name"] = $user["firstname"];
            $_SESSION["user_role"] = $user["role"];
            $_SESSION["success_message"] = "Вы успешно вошли!";
            $stmt->close();
            $conn->close();
            header("Location: index.php");
            exit();
        } else {            
            $_SESSION["error_message"] = "Неверный email пользователя или пароль!";
            header("Location: login.php");
            exit();
        }
    } else {        
        $_SESSION["error_message"] = "Неверный email пользователя или пароль!";
        header("Location: login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
