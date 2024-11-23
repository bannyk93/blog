<?php
session_start();
require 'db.php';

if (isset($_POST["login"]) && isset($_POST["password"])) {
    $login = mysqli_real_escape_string($link, $_POST['login']);
    $password = mysqli_real_escape_string($link, $_POST['password']);

    $query = "SELECT * FROM users WHERE login='$login'";
    $res = mysqli_query($link, $query);
    if (mysqli_num_rows($res) > 0) { 
        echo "<p>Пользователь с таким логином уже существует.</p>"; 
        echo '<a href="index.php">Вернуться</a>';
        exit(); 
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $link->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $login, $passwordHash);
    if ($stmt->execute()) {
        echo '<p>Регистрация прошла успешно!</p>';
        echo '<a href="index.php">Войти</a>';
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    }
}
?>
