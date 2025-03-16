<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        <?php
            include"style/register.css"
        ?>
    </style>
</head>
<body>
    <form action="data_register.php" method="POST" autocomplete="off">
        <div>
            <h3>Регистрация</h3>
        <input type="text" name="firstname" placeholder="Имя" required>
        <?php
            if(isset($_SESSION["error_name"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_name]</p>
                </div>";
                unset($_SESSION["error_name"]);
            }
        ?>
        <input type="text"  name="lastname"placeholder="Фамилия">
        <?php
        if(isset($_SESSION["error_lastname"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_lastname]</p>
                </div>";
                unset($_SESSION["error_lastname"]);
            }
        ?>
        <div class="optionGender">
        <label for="male" >Мужчина</label>
        <input type="radio" id="male" name="gender" value="мужчина">        
        </div>
        <div class="optionGender">
        <label for="female" >Женщина</label>
        <input type="radio" id="female" name="gender" value="женщина" required>        
        </div>
        <input type="email" name="email" placeholder="Email" required>
        <?php
        if(isset($_SESSION["error_email"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_email]</p>
                </div>";
                unset($_SESSION["error_email"]);
            }
        ?>
        <input type="tel" id="numberPhone" name="phone" placeholder="Телефон в формате +373...">
        <script>
            document.getElementById("numberPhone").addEventListener("input", function()
        {
            let value=this.value
            if (!/^\+(\d*)$/.test(value)){
                this.value=value.substring(0, value.length-1)
            }
        });
        </script>
        <?php
        if(isset($_SESSION["error_phone"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_phone]</p>
                </div>";
                unset($_SESSION["error_phone"]);
            }
        ?>
        <input type="password"  name="password" placeholder="пароль" required>
        <?php
        if(isset($_SESSION["error_password"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_password]</p>
                </div>";
                unset($_SESSION["error_password"]);
            }
        ?>
        <input type="password" name="confirmPassword" placeholder="проверка пароля" required>        
        <?php
        if(isset($_SESSION["error_confirmPassword"]))
            {
                echo "<div class='error'>
                <p>$_SESSION[error_confirmPassword]</p>
                </div>";
                unset($_SESSION["error_confirmPassword"]);

                
            }
        
        if(isset($_SESSION["error_message"]))
        {
            echo "<div class='error'>
                <p>$_SESSION[error_message]</p>
                </div>";
                unset($_SESSION["error_message"]);
        }
        ?>
        <button type="submit">Регистрация</button>
        <div class="haveAcc">
        <p>уже есть аккаунт?</p>
        <a href="login.php">Войти</a>   
        </div>
        </div>
    </form>
    
</body>
</html>