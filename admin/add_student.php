<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check session variables
echo "<!-- Session Debug: " . print_r($_SESSION, true) . " -->";

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);

if (!$data) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['add_student'])) {
    $username = $_POST['name'];
    $user_email = $_POST['email'];
    $user_phone = $_POST['phone'];
    $user_password = $_POST['password'];
    $usertype = "student";


    $check = "SELECT * FROM user WHERE username='$username'";
    $check_user = mysqli_query($data, $check);

    if (!$check_user) {
        echo "Database error: " . mysqli_error($data);
        exit;
    }

    $row_count = mysqli_num_rows($check_user);

    if ($row_count >= 1) {
        echo "Username Already Exist. Try another one";
        exit;
    }

    $sql = "INSERT INTO user (username, email, phone, usertype, password) VALUES ('$username', '$user_email', '$user_phone', '$usertype', '$user_password')";

    $result = mysqli_query($data, $sql);
    if ($result) {
        echo "<script type='text/javascript'>
        alert('Data uploaded successfully')
        </script>";
    } else {
        echo "Upload failed: " . mysqli_error($data);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Add Student</title>
    <style type="text/css">
        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .div_dig {
            background-color: skyblue;
            width: 400px;
            padding-top: 70px;
            padding-bottom: 70px;
        }
    </style>
</head>


<body>
    <header class="header">
        <a href="adminhome.php">Admin Dashboard</a>
        <div class="logout">
            <a href="../logout.php">Logout</a>
        </div>
    </header>


    <aside>
        <ul>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="add_student.php">Add Student</a></li>
            <li><a href="../students/view_student.php">View Student</a></li>
            <li><a href="admin_add_teacher.php">Add Teacher</a></li>
            <li><a href="admin_view_teacher.php">View Teacher</a></li>
            <li><a href="admin_add_courses.php">Add Courses</a></li>
            <li><a href="admin_view_courses.php">View Courses</a></li>

        </ul>
    </aside>
    <div class="content">
        <center>
            <h1>add_student</h1>

            <div class="div_dig">
                <form action="#" method="POST">
                    <div>
                        <label for="">Username</label>
                        <input type="text" name="name">
                    </div>

                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email">
                    </div>

                    <div>
                        <label for="">Phone</label>
                        <input type="number" name="phone">
                    </div>


                    <div>
                        <label for="">Password</label>
                        <input type="password" name="password">
                    </div>


                    <div>
                        <input type="submit" name="add_student">
                    </div>
                </form>
            </div>


    </div>
    </center>



</body>

</html>