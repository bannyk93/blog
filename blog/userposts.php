<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

if (!isset($_GET['user_id'])) {
    header('Location: blogpage.php');
    exit;
}

$view_user_id = intval($_GET['user_id']);

$stmt = $link->prepare("SELECT login FROM users WHERE id = ?");
$stmt->bind_param('i', $view_user_id);
$stmt->execute();
$stmt->bind_result($view_login);
if (!$stmt->fetch()) {
    echo '<p>Пользователь не найден</p>';
    echo '<a href="blogpage.php">Назад</a>';
    exit;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Посты пользователя <?php echo htmlspecialchars($view_login); ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Посты пользователя <?php echo htmlspecialchars($view_login); ?></h1>
        <button onclick="location.href='blogpage.php';">Назад</button>
        <div class="posts">
            <?php
            $sql = "SELECT id, post, date_of_publ, tags FROM posts WHERE user_id = $view_user_id ORDER BY date_of_publ DESC";
            $result = mysqli_query($link, $sql);

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="post">';
                echo '<p class="post-content">' . nl2br(htmlspecialchars($row['post'])) . '</p>';
                echo '<p class="post-meta">Дата публикации: ' . $row['date_of_publ'] . '</p>';
                echo '<p class="post-tags">Теги: ' . htmlspecialchars($row['tags']) . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</body>
</html>
