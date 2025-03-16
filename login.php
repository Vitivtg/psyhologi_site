<?php 
 session_start();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        <?php
            include"style/login.css"
        ?>
    </style>
</head>
<body>
    <?php
    /*if (isset($_SESSION["success_message"]) || isset($_SESSION["success_message1"])) {
        echo "<div class='success'>";
        if (isset($_SESSION["success_message"])) {
            echo "<p>" . htmlspecialchars($_SESSION["success_message"]) . "</p>";
            unset($_SESSION["success_message"]);
        }
        if (isset($_SESSION["success_message1"])) {
            echo "<p>" . htmlspecialchars($_SESSION["success_message1"]) . "</p>";
            unset($_SESSION["success_message1"]);
        }
        echo "</div>";
    }*/
    ?>

    <div class="form-container">
        <div class="form">  
        <?php
                    if (isset($_SESSION["success_message"])&&isset($_SESSION["success_message1"])) {
                        echo "<div class='success'>";
                        echo "<p>" . htmlspecialchars($_SESSION["success_message"]) . "</p>";
                        echo "<p>" . htmlspecialchars($_SESSION["success_message1"]) . "</p>";
                        unset($_SESSION["success_message"]);
                        unset($_SESSION["success_message1"]);
                        echo "</div>";
                    }
                ?>          
            <form action="data_login.php" method="POST">
                <h3>Вход</h3>        
                <input type="email" name="email" placeholder="Email" >        
                <input type="password" name="password" placeholder="пароль" >        
                <button type="submit">Вход</button>
                <?php
                    if (isset($_SESSION["error_message"])) {
                        echo "<div class='error'>";
                        echo "<p>" . htmlspecialchars($_SESSION["error_message"]) . "</p>";
                        unset($_SESSION["error_message"]);
                        echo "</div>";
                    }                    
                ?>
                <div class="haveAcc">
                    <p>нет аккаунта?</p>
                    <a href="register.php">Регистрация</a>   
                </div>
            </form>            
        </div>
    </div>    
</body>

</html>