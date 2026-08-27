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
$sql = "SELECT * FROM teacher";
$result = mysqli_query($data, $sql);



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
        $imageFolder = dirname(__DIR__) . '/image';
        if (!is_dir($imageFolder)) {
            mkdir($imageFolder, 0755, true);
        }
        $dst = $imageFolder . '/' . $file;
        $dst_db = "../image/" . $file;
        move_uploaded_file($_FILES['image']['tmp_name'], $dst);
        $sql2 = "UPDATE teacher SET name='$t_name',description='$t_des',image='$dst_db',password='$t_pass' WHERE id='$t_id';";
    } else {
        $sql2 = "UPDATE teacher SET name='$t_name',description='$t_des',password='$t_pass' WHERE id='$t_id';";
    }

    $result2 = mysqli_query($data, $sql2);

    if ($result2) {
        header('Location: admin_view_teacher.php');
        exit;
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
            <li><a href="admin_add_courses.php">Add Courses</a></li>
            <li><a href="admin_view_courses.php">View Courses</a></li>
            <li><a href="admin_add_teacher.php">Add Teacher</a></li>
            <li><a href="admin_view_teacher.php">View Teacher</a></li>
            <li><a href="admin_add_courses.php">Add Courses</a></li>
            <li><a href="admin_view_courses.php">View Courses</a></li>
        </ul>
    </aside>
    <div class="content">
        <center>
            <h1>Update Teacher Data</h1>

            <form id="updateTeacherForm" class="form_deg" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="teacher_id" value="<?php echo $t_id; ?>">
                <div>
                    <label>Teacher Name</label>
                    <input type="text" name="name" value="<?php echo $info['name']; ?>" required pattern="[A-Za-z ]+">

                </div>
                <div>
                    <label>About teacher</label>
                    <textarea name="description" required><?php echo $info['description']; ?></textarea>

                </div>
                <div>
                    <label>Teacher Old Image</label>
                    <img src="<?php echo $info['image']; ?>" alt="" width="150">

                </div>
                <div>
                    <label>Teacher New Image</label>
                    <input type="file" name="image" accept="image/png,image/jpeg">

                </div>

                <div>
                    <label>password </label>
                    <input type="password" name="password" value="<?php echo "{$info['password']}" ?>" required minlength="6">

                </div>
                <div class="btn-submit">

                    <input type="submit" name="update_teacher" value="Update Teacher">

                </div>
            </form>
        </center>


    </div>
    <script>
        document.getElementById("updateTeacherForm").addEventListener("submit", function(e) {

            let name = document.getElementsByName("name")[0].value.trim();
            let description = document.getElementsByName("description")[0].value.trim();
            let password = document.getElementsByName("password")[0].value.trim();
            let image = document.getElementsByName("image")[0];

            // Teacher name
            if (name === "") {
                alert("Please enter the teacher name.");
                e.preventDefault();
                return;
            }

            // Only letters and spaces
            let namePattern = /^[A-Za-z\s]+$/;
            if (!namePattern.test(name)) {
                alert("Teacher name should contain only letters.");
                e.preventDefault();
                return;
            }

            // Description
            if (description === "") {
                alert("Please enter the teacher description.");
                e.preventDefault();
                return;
            }

            // Password
            if (password === "") {
                alert("Please enter the password.");
                e.preventDefault();
                return;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                e.preventDefault();
                return;
            }

            // Validate image only if a new image is selected
            if (image.files.length > 0) {
                let allowedTypes = ["image/jpeg", "image/jpg", "image/png"];

                if (!allowedTypes.includes(image.files[0].type)) {
                    alert("Only JPG, JPEG, and PNG images are allowed.");
                    e.preventDefault();
                    return;
                }
            }

        });
    </script>



</body>

</html>
