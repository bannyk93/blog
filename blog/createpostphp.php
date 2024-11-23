<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if (isset($_POST["post"]) && isset($_POST["tags"])) {
    $post = mysqli_real_escape_string($link, $_POST['post']);
    $tags = mysqli_real_escape_string($link, $_POST['tags']);
    $userId = $_SESSION['user_id'];
    $is_hidden = isset($_POST['is_hidden']) ? 1 : 0;

    $stmt = $link->prepare("INSERT INTO posts (post, tags, user_id, is_hidden) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssii', $post, $tags, $userId, $is_hidden);
    if ($stmt->execute()) {
        header('Location: blogpage.php');
        exit;
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        echo '<a href="createpost.php">Вернуться</a>';
    }
}
?>
