<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if (!isset($_GET['id'])) {
    header('Location: blogpage.php');
    exit;
}

$post_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

$stmt = $link->prepare("DELETE FROM comments WHERE post_id = ?");
$stmt->bind_param('i', $post_id);
$stmt->execute();

$stmt = $link->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $user_id);
if ($stmt->execute()) {
    header('Location: blogpage.php');
    exit;
} else {
    echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
    echo '<a href="blogpage.php">Вернуться</a>';
}
?>
