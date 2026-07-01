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



if (isset($_GET['teacher_id'])) {
    $t_id = $_GET['teacher_id'];
    $sql = "SELECT * FROM teacher WHERE id='$t_id'";
    $result = mysqli_query($data, $sql);
    $info = $result->fetch_assoc();
}

if (isset($_POST['update_teacher'])) {
    $t_id = $_POST['teacher_id'];
    $t_name = $_POST['name'];
    $t_des = $_POST['description'];
    $t_pass = $_POST['password'];
    $file = $_FILES['image']['name'];

    if ($file) {
        $dst = "./image/" . $file;
        $dst_db = "image/" . $file;
        move_uploaded_file($_FILES['image']['tmp_name'], $dst);
        $sql2 = "UPDATE teacher SET name='$t_name',description='$t_des',image='$dst_db',password='$t_pass' WHERE id='$t_id';";
    } else {
        $sql2 = "UPDATE teacher SET name='$t_name',description='$t_des',password='$t_pass' WHERE id='$t_id';";
    }

    $result2 = mysqli_query($data, $sql2);

    if ($result2) {
        header('location:admin_view_teacher.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Admin homet</title>
    <style>
        label {
            display: inline;
            width: 150px;
            text-align: right;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .form_deg {
            background-color: #87CEEB;
            max-width: 600px;
            width: 90%;
            margin: 30px auto;
            padding: 40px 30px;
            box-sizing: border-box;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }
    </style>
</head>


<body>
    <header class="header">
        <a href=" ">Admin Deshboard</a>
        <div class="logout">
            <a href="logout.php">Logout</a>
        </div>
    </header>


    <aside>
        <ul>
            <li><a href="admission.php">Admission</a></li>
            <li><a href="add_student.php">Add Student</a></li>
            <li><a href="view_student.php">View Student</a></li>
            <li><a href="">Add Teacher</a></li>
            <li><a href="">View Teacher</a></li>
            <li><a href="">Add Courses</a></li>
            <li><a href="">View Courses</a></li>
        </ul>
    </aside>
    <div class="content">
        <center>
            <h1>Update Teacher Data</h1>

            <form class="form_deg" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="teacher_id" value="<?php echo $t_id; ?>">
                <div>
                    <label>Teacher Name</label>
                    <input type="text" name="name" value="<?php echo $info['name']; ?>">

                </div>
                <div>
                    <label>About teacher</label>
                    <textarea name="description"><?php echo $info['description']; ?></textarea>

                </div>
                <div>
                    <label>Teacher Old Image</label>
                    <img src="<?php echo $info['image']; ?>" alt="" width="150">

                </div>
                <div>
                    <label>Teacher New Image</label>
                    <input type="file" name="image">

                </div>

                <div>
                    <label>password </label>
                    <input type="text" name="password" value="<?php echo "{$info['password']}" ?>">

                </div>
                <div class="btn-submit">

                    <input type="submit" name="update_teacher" value="Update Teacher">

                </div>
            </form>
        </center>


    </div>



</body>

</html>