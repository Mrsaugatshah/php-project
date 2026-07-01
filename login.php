<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W-School Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-page">
    <div class="login-panel">
        <h1>Login to W-School</h1>
        <h4>
            <?php

            error_reporting(0);
            session_start();
            echo $_SESSION['loginMessage'];
            session_destroy();
            ?>
        </h4>
        <form action="login_check.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" autocomplete="username" placeholder="Enter your username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password">
            </div>
            <div class="form-action">
                <input type="submit" class="submit-btn" name="submit" value="Login">
            </div>
        </form>
    </div>
</body>

</html>