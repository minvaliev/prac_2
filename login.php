<?php
    session_start();

    $connection = new PDO('mysql:host=localhost; dbname=forum; charset=utf8', 'root', '' );
    $login = $connection->query("SELECT * FROM `admin`");

    if ($_POST['login'] && $_POST['password']) {
        foreach ($login as $admins) {
            if ($_POST['login'] == $admins['login'] && $_POST['password'] == $admins['password']) {
                $_SESSION['login'] = $_POST['login'];
                $_SESSION['password'] = $_POST['password'];
                header("Location: admin.php");
            }
        }
        echo "Неверный логин или пароль!";
    }


    if ($_POST['but']) {
        header("Location: registr.php");
    }

?>

<style>
    body {
        margin: 200px 300px;
        background-color: #2aabd2;
    }
    input, p {
        font-size: 30px;
        margin: 10px;
    }
</style>

<form method="POST">
    <p>Авторизируйтесь пожалуйста!</p>
    <input type="text" name="login" required placeholder="Введите логин" > <br>
    <input type="password" name="password" required placeholder="Введите пароль"> <br>
    <input type="submit">
</form>


