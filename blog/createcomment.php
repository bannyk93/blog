<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if (isset($_POST["comment"]) && isset($_POST["post_id"])) {
    $comment = mysqli_real_escape_string($link, $_POST['comment']);
    $post_id = intval($_POST['post_id']);
    $user_posted = $_SESSION['user_id'];

    $stmt = $link->prepare("INSERT INTO comments (comment, post_id, user_posted) VALUES (?, ?, ?)");
    $stmt->bind_param('sii', $comment, $post_id, $user_posted);
    if ($stmt->execute()) {
        header('Location: blogpage.php');
        exit;
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        echo '<a href="blogpage.php">Вернуться</a>';
    }
}
?>
