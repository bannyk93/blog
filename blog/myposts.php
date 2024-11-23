<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Мои посты</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Мои посты</h1>
        <button onclick="location.href='blogpage.php';">Назад</button>
        <div class="posts">
            <?php
            $sql = "SELECT id, post, date_of_publ, tags FROM posts WHERE user_id = $user_id ORDER BY date_of_publ DESC";
            $result = mysqli_query($link, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post">';
                echo '<p class="post-content">' . nl2br(htmlspecialchars($row['post'])) . '</p>';
                echo '<p class="post-meta">Дата публикации: ' . $row['date_of_publ'] . '</p>';
                echo '<p class="post-tags">Теги: ' . htmlspecialchars($row['tags']) . '</p>';
                echo '<div class="post-actions">';
                echo '<button onclick="location.href=\'editpost.php?id=' . $row['id'] . '\';">Редактировать</button>';
                echo '<button onclick="location.href=\'deletepost.php?id=' . $row['id'] . '\';">Удалить</button>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
