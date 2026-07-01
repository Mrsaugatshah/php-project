<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check session variables
echo "<!-- Session Debug: " . print_r($_SESSION, true) . " -->";

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);

if (isset($_POST['add_teacher'])) {
    $t_name = $_POST['name'];
    $t_description = $_POST['description'];
    $file = $_FILES['image']['name'];
    $dst = './image/' . $file;
    $dst_db = "image/" . $file;
    $t_password = $_POST['password'];
    move_uploaded_file($_FILES['image']['tmp_name'], $dst);
    $sql = "INSERT INTO teacher(name,description,image,password)
    VALUES('$t_name','$t_description','$dst_db','$t_password')";
    mysqli_query($data, $sql);

    $sql_user = "INSERT INTO user(username,password,usertype,email,phone)
    VALUES('$t_name','$t_password','teacher','','')";
    mysqli_query($data, $sql_user);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>admin home</title>
    <style>
        .content {
            margin-left: 220px;
            margin-top: 70px;
            padding: 24px;
            min-height: calc(100vh - 70px);
        }

        .form-wrapper {
            max-width: 560px;
            margin: 0 auto;
            padding: 24px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08);
        }

        .form-wrapper h1 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #1e293b;
        }

        .div_deg,
        .form-row {
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-row label,
        .div_deg label {
            font-weight: 600;
        }

        .form-row input,
        .div_deg input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            background: #f8fafc;
            font-size: 15px;
        }

        .form-row input:focus,
        .div_deg input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .form-actions {
            margin-top: 12px;
            text-align: right;
        }

        .form-actions input[type="submit"] {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            background: #2563eb;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
        }

        .form-actions input[type="submit"]:hover {
            background: #1d4ed8;
        }

        @media (max-width: 800px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }

            aside {
                position: static;
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>


<body>
    <header class="header">
        <a href=" "> Add Teacher</a>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>


    <aside>
        <ul>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="add_student.php">Add Student</a></li>
            <li><a href="view_student.php">View Student</a></li>
            <li><a href="admin_add_teacher.php">Add Teacher</a></li>
            <li><a href="admin_view_teacher.php">View Teacher</a></li>
            <li><a href="admin_add_courses.php">Add Courses</a></li>
            <li><a href="">View Courses</a></li>
        </ul>
    </aside>
    <div class="content">
        <div class="form-wrapper">
            <h1>Add Teacher</h1>
            <form action="#" method="POST" enctype="multipart/form-data">
                <div class="div_deg">
                    <label>Teacher Name</label>
                    <input type="text" name="name" class="btn-name" placeholder="Enter teacher name">
                </div>

                <div class="form-row">
                    <label>Description</label>
                    <input type="text" name="description" class="btn-description" placeholder="Enter description">
                </div>

                <div class="form-row">
                    <label>Image</label>
                    <input type="file" name="image">
                </div>

                <div>
                    <label>password </label>
                    <input type="text" name="password" placeholder="Enter password">

                </div>

                <div class="form-actions">
                    <input type="submit" name="add_teacher" value="Add Teacher">
                </div>
            </form>
        </div>
    </div>



</body>

</html>