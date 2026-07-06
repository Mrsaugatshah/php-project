<?php
error_reporting(0);
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("connection error");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($data, trim($_POST['username']));
    $pass = mysqli_real_escape_string($data, trim($_POST['password']));

    $sql = "SELECT * FROM user WHERE username='$name' AND password='$pass'";
    $result = mysqli_query($data, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        if ($row["usertype"] === "student") {
            $_SESSION['username'] = $name;
            $_SESSION['usertype'] = "student";
            header("Location: students/student.home.php");
            exit;
        } elseif ($row["usertype"] === "admin") {
            $_SESSION['username'] = $name;
            $_SESSION['usertype'] = "admin";
            header("Location: admin/adminhome.php");
            exit;
        } elseif ($row["usertype"] === "teacher") {
            $_SESSION['username'] = $name;
            $_SESSION['usertype'] = "teacher";
            header("Location: teachers/teacher_home.php");
            exit;
        }
    }

    $message = "Username or password does not match";
    $_SESSION['loginMessage'] = $message;
    header("Location: login.php");
    exit;
}
