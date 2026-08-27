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
        <form id="loginForm" action="login_check.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input id="username" type="text" name="username" autocomplete="username" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required minlength="6">
            </div>
            <div class="form-action">
                <input type="submit" class="submit-btn" name="submit" value="Login">
            </div>
        </form>
    </div>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();

            // Username validation
            if (username === "") {
                alert("Invalid credentials.");
                document.getElementById("username").focus();
                event.preventDefault();
                return;
            }

            // Password validation
            if (password === "") {
                alert("Invalid credentials.");
                document.getElementById("password").focus();
                event.preventDefault();
                return;
            }

            // Minimum password length
            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                document.getElementById("password").focus();
                event.preventDefault();
                return;
            }
        });
    </script>
</body>

</html>
