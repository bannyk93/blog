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

$stmt = $link->prepare("SELECT post, tags FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$stmt->bind_result($post_content, $post_tags);
if (!$stmt->fetch()) {
    echo '<p>Пост не найден или у вас нет прав на его редактирование</p>';
    echo '<a href="blogpage.php">Назад</a>';
    exit;
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Редактировать пост</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Редактировать пост</h1>
        <form method="POST" action="updatepost.php">
            <input type="hidden" name="id" value="<?php echo $post_id; ?>">
            <label>Содержание поста:<br>
                <textarea name="post" rows="10" cols="80" required><?php echo htmlspecialchars($post_content); ?></textarea>
            </label><br>
            <label>Теги (через запятую):<br>
                <input type="text" name="tags" value="<?php echo htmlspecialchars($post_tags); ?>" required>
            </label><br>
            <input type="submit" value="Обновить">
            <button type="button" onclick="history.back();">Отмена</button>
        </form>
    </div>
</body>
</html>
