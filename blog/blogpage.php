<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

$user_id = $_SESSION['user_id'];

$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
$tagFilter = '';
if (isset($_GET['tag']) && !empty($_GET['tag'])) {
    $tag = mysqli_real_escape_string($link, $_GET['tag']);
    $tagFilter = " AND posts.tags LIKE '%$tag%'";
}

if ($filter === 'subscribed') {
    $subscribed_ids = [];
    $sub_sql = "SELECT sub_user FROM subscr WHERE who_user = $user_id";
    $sub_res = mysqli_query($link, $sub_sql);
    while ($sub = mysqli_fetch_assoc($sub_res)) {
        $subscribed_ids[] = $sub['sub_user'];
    }

    if (!empty($subscribed_ids)) {
        $ids = implode(',', $subscribed_ids);
        $sql = "SELECT posts.id, posts.post, users.login, posts.date_of_publ, posts.tags, posts.is_hidden, posts.user_id 
                FROM posts 
                JOIN users ON users.id = posts.user_id 
                WHERE posts.user_id IN ($ids) $tagFilter AND (posts.is_hidden = 0 OR posts.user_id = $user_id)
                ORDER BY posts.date_of_publ DESC";
    } else {
        $sql = "";
    }
} else {
    $sql = "SELECT posts.id, posts.post, users.login, posts.date_of_publ, posts.tags, posts.is_hidden, posts.user_id 
            FROM posts 
            JOIN users ON users.id = posts.user_id 
            WHERE (posts.is_hidden = 0 OR posts.user_id = $user_id)
            $tagFilter
            ORDER BY posts.date_of_publ DESC";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Мой блог</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Привет, <?php echo htmlspecialchars($_SESSION["login"]); ?>! Добро пожаловать в блог!</h1>
        <div class="buttons">
            <button onclick="location.href='subscribe.php';">Мои подписки</button>
            <button onclick="location.href='myposts.php';">Мои посты</button>
            <button onclick="location.href='createpost.php';">Создать пост</button>
            <button onclick="location.href='logout.php';">Выйти</button>
        </div>
        <div class="filter">
            <h2>Фильтр по тегам</h2>
            <form method="GET" action="blogpage.php">
                <input type="text" name="tag" placeholder="Введите тег" value="<?php echo isset($_GET['tag']) ? htmlspecialchars($_GET['tag']) : ''; ?>">
                <input type="submit" value="Фильтровать">
            </form>
            <h2>Отображение постов</h2>
            <form method="GET" action="blogpage.php">
                <input type="radio" id="all" name="filter" value="all" <?php if ($filter === 'all') echo 'checked'; ?>>
                <label for="all">Все посты</label><br>
                <input type="radio" id="subscribed" name="filter" value="subscribed" <?php if ($filter === 'subscribed') echo 'checked'; ?>>
                <label for="subscribed">Только подписанные</label><br>
                <?php if (isset($_GET['tag']) && !empty($_GET['tag'])): ?>
                    <input type="hidden" name="tag" value="<?php echo htmlspecialchars($_GET['tag']); ?>">
                <?php endif; ?>
                <input type="submit" value="Применить">
            </form>
        </div>
        <div class="posts">
            <?php
            if (!empty($sql)) {
                $result = mysqli_query($link, $sql);

                if ($filter === 'subscribed' && empty($subscribed_ids)) {
                    echo "<p>Вы не подписаны ни на одного пользователя</p>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['is_hidden'] && $row['user_id'] != $user_id) {
                            continue; 
                        }

                        echo '<div class="post">';
                        echo '<p class="post-content">' . nl2br(htmlspecialchars($row['post'])) . '</p>';
                        echo '<p class="post-meta">Автор: <a href="userposts.php?user_id=' . $row['user_id'] . '">' . htmlspecialchars($row['login']) . '</a> | Дата: ' . $row['date_of_publ'] . '</p>';
                        echo '<p class="post-tags">Теги: ' . htmlspecialchars($row['tags']) . '</p>';

                        if ($_SESSION['user_id'] == $row['user_id']) {
                            echo '<div class="post-actions">';
                            echo '<button onclick="location.href=\'editpost.php?id=' . $row['id'] . '\';">Редактировать</button>';
                            echo '<button onclick="location.href=\'deletepost.php?id=' . $row['id'] . '\';">Удалить</button>';
                            echo '</div>';
                        }

                        echo '<div class="comments">';

                        $post_id = $row['id'];
                        $comments_sql = "SELECT comments.comment, users.login, comments.id 
                                         FROM comments 
                                         JOIN users ON users.id = comments.user_posted 
                                         WHERE comments.post_id = $post_id
                                         ORDER BY comments.id ASC";
                        $comments_res = mysqli_query($link, $comments_sql);
                        while ($comment = mysqli_fetch_assoc($comments_res)) {
                            echo '<div class="comment">';
                            echo '<p><strong>' . htmlspecialchars($comment['login']) . ':</strong> ' . htmlspecialchars($comment['comment']) . '</p>';
                            echo '</div>';
                        }
                
                        echo '<form method="POST" action="createcomment.php">';
                        echo '<input type="hidden" name="post_id" value="' . $post_id . '">';
                        echo '<textarea name="comment" required></textarea><br>';
                        echo '<input type="submit" value="Комментировать">';
                        echo '</form>';
                        echo '</div>'; 

                        echo '</div>';
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
