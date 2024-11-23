<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if (isset($_POST["id"]) && isset($_POST["post"]) && isset($_POST["tags"])) {
    $post_id = intval($_POST['id']);
    $post = mysqli_real_escape_string($link, $_POST['post']);
    $tags = mysqli_real_escape_string($link, $_POST['tags']);
    $user_id = $_SESSION['user_id'];

    $stmt = $link->prepare("UPDATE posts SET post = ?, tags = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ssii', $post, $tags, $post_id, $user_id);
    if ($stmt->execute()) {
        header('Location: blogpage.php');
        exit;
    } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
        echo '<a href="editpost.php?id=' . $post_id . '">Вернуться</a>';
    }
}
?>
