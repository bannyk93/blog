<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Создать пост</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Новый пост от <?php echo htmlspecialchars($_SESSION["login"]); ?></h1>
        <form name="createpost" method="POST" action="createpostphp.php">
            <label>Содержание поста:<br>
                <textarea name="post" rows="10" cols="80" required></textarea>
            </label><br>
            <label>Теги (через запятую):<br>
                <input type="text" name="tags" required>
            </label><br>
            <label>
                <input type="checkbox" name="is_hidden"> Скрытый пост
            </label><br><br>
            <input type="submit" name="send" value="Запостить">
            <button type="button" onclick="history.back();">Назад</button>
        </form>
    </div>
</body>
</html>
