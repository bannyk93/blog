<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>Блог</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать в блог!</h1>
        
        <div class="form-container">
            <h2>Регистрация</h2>
            <form name="registerForm" method="POST" action="reg.php">
                <label>Логин:<br><input type="text" name="login" required></label><br>
                <label>Пароль:<br><input type="password" name="password" required></label><br>
                <input type="submit" name="send" value="Зарегистрироваться">
            </form>
        </div>
        
        <div class="form-container">
            <h2>Авторизация</h2>
            <form name="signInForm" method="POST" action="auth.php">
                <label>Логин:<br><input type="text" name="login" required></label><br>
                <label>Пароль:<br><input type="password" name="password" required></label><br>
                <input type="submit" name="send" value="Войти">
            </form>
        </div>
    </div>
</body>
</html>
