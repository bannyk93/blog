<?php
session_start();
if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}
require 'db.php';

$user_id = $_SESSION['user_id'];

if (isset($_POST['subscribe_user_id'])) {
    $subscribe_user_id = intval($_POST['subscribe_user_id']);
    if ($subscribe_user_id != $user_id) { 
        $check = $link->prepare("SELECT id FROM users WHERE id = ?");
        $check->bind_param('i', $subscribe_user_id);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $check->close();
            $stmt = $link->prepare("SELECT id FROM subscr WHERE who_user = ? AND sub_user = ?");
            $stmt->bind_param('ii', $user_id, $subscribe_user_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows == 0) {
                $stmt->close();
                $stmt = $link->prepare("INSERT INTO subscr (who_user, sub_user) VALUES (?, ?)");
                $stmt->bind_param('ii', $user_id, $subscribe_user_id);
                $stmt->execute();
                echo '<p>Вы успешно подписались</p>';
            } else {
                echo '<p>Вы уже подписаны на этого пользователя</p>';
                $stmt->close();
            }
        } else {
            echo '<p>Пользователь не найден</p>';
        }
    } else {
        echo '<p>Вы не можете подписаться на себя</p>';
    }
}

$sql = "SELECT id, login FROM users WHERE id != $user_id";
$result = mysqli_query($link, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <title>Мои подписки</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Подписаться на пользователя</h1>
        <button onclick="location.href='blogpage.php';">Назад</button>
        <div class="users">
            <form method="POST" action="subscribe.php">
                <label for="subscribe_user_id">Выберите пользователя:</label><br>
                <select name="subscribe_user_id" required>
                    <?php
                    while ($user = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $user['id'] . '">' . htmlspecialchars($user['login']) . '</option>';
                    }
                    ?>
                </select><br><br>
                <input type="submit" value="Подписаться">
            </form>
        </div>
        <h2>Ваши подписки</h2>
        <div class="subscriptions">
            <?php
            $stmt = $link->prepare("SELECT users.id, users.login FROM subscr JOIN users ON subscr.sub_user = users.id WHERE subscr.who_user = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $subs_res = $stmt->get_result();
            while ($sub = mysqli_fetch_assoc($subs_res)) {
                echo '<p>' . htmlspecialchars($sub['login']) . '</p>';
            }
            ?>
        </div>
    </div>
</body>
</html>
