<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$db = 'schoolproject';

$data = mysqli_connect($host, $user, $password, $db);
if (!$data) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$id = $_GET['student_id'];
$info = null;

// Handle form submission
if (isset($_POST['update_student'])) {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $sql = "UPDATE user SET username='$username', email='$email', phone='$phone', password='$password' WHERE id='$id'";
    $result = mysqli_query($data, $sql);
    if ($result) {
        $_SESSION['message'] = 'Student updated successfully';
        header('Location: ../students/view_student.php');
        exit;
    }
}

// Fetch student data
$sql = "SELECT * FROM user WHERE id='$id'";
$result = mysqli_query($data, $sql);
if (!$result) {
    die('Query failed: ' . mysqli_error($data));
}

$info = $result->fetch_assoc();
if (!$info) {
    $_SESSION['message'] = 'Student not found';
    header('Location: ../students/view_student.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Update Student</title>
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
            <li>
                <a href="admission.php">Admission</a>
            </li>
        </ul>

        <ul>
            <li>
                <a href="add_student.php">Add Student</a>
            </li>
        </ul>

        <ul>
            <li>
                <a href="../students/view_student.php">View Student</a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="admin_add_teacher.php">Add Teacher</a>
            </li>
        </ul>
        <ul>
            <li>
                <a href="admin_view_teacher.php">View Teacher</a>
            </li>
        </ul>

        <ul>
            <li>
                <a href="admin_add_courses.php">Add Courses</a>
            </li>
        </ul>

        <ul>
            <li>
                <a href="admin_view_courses.php">View Courses</a>
            </li>
        </ul>
    </aside>

    <div class="content">
        <center>
            <h1>Update Student</h1>

            <div class="div_dig">
                <form action="#" method="POST">
                    <div>
                        <label for="">Username</label>
                        <input type="text" name="name" value="<?php echo "{$info['username']}"; ?>">
                    </div>

                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email" value="<?php echo "{$info['email']}"; ?>">
                    </div>

                    <div>
                        <label for="">Phone</label>
                        <input type="number" name="phone" value="<?php echo "{$info['phone']}"; ?>">
                    </div>

                    <div>
                        <label for="">Password</label>
                        <input type="password" name="password" value="<?php echo "{$info['password']}"; ?>">
                    </div>

                    <div>
                        <input type="submit" name="update_student" value="Update">
                    </div>
                </form>
            </div>
        </center>
    </div>

</body>

</html>