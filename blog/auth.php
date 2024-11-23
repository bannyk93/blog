<?php
session_start();
require 'db.php';

if (!empty($_POST['password']) && !empty($_POST['login'])) {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE login='$login'";
    $res = mysqli_query($link, $query);
    $user = mysqli_fetch_assoc($res);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['auth'] = true;
        $_SESSION['login'] = $user['login'];
        $_SESSION['user_id'] = $user['id'];

        header('Location: blogpage.php');
        exit;
    } else {
        echo "<p>Неверно введен логин или пароль</p>";
        echo '<a href="index.php">Назад</a>';
    }
} else {
    echo "<p>Пожалуйста, заполните все поля</p>";
    echo '<a href="index.php">Назад</a>';
}
?>
