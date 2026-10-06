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
    $username = trim($_POST['name'] ?? '');
    $user_email = trim($_POST['email'] ?? '');
    $user_phone = trim($_POST['phone'] ?? '');
    $user_password = $_POST['password'] ?? '';
    $usertype = "student";

    $photo = $_FILES['photo'] ?? null;
    if (!$photo || $photo['error'] !== UPLOAD_ERR_OK || $photo['size'] <= 0 || $photo['size'] > 2 * 1024 * 1024) {
        die('Please choose a photo no larger than 2 MB.');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($photo['tmp_name']);
    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png'];
    if (!isset($extensions[$mime]) || getimagesize($photo['tmp_name']) === false) {
        die('Photo must be a valid JPG or PNG image.');
    }

    $uploadDir = __DIR__ . '/../uploads/students';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
        die('Could not create the student photo folder.');
    }
    $photoName = date('Ymd_His') . '_' . bin2hex(random_bytes(8)) . '.' . $extensions[$mime];
    if (!move_uploaded_file($photo['tmp_name'], $uploadDir . '/' . $photoName)) {
        die('Could not save the student photo.');
    }

    $stmt = mysqli_prepare($data, 'INSERT INTO user (username, email, phone, usertype, password, image) VALUES (?, ?, ?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssssss', $username, $user_email, $user_phone, $usertype, $user_password, $photoName);
    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        echo "<script type='text/javascript'>
        alert('Data uploaded successfully')
        </script>";
    } else {
        @unlink($uploadDir . '/' . $photoName);
        echo mysqli_stmt_errno($stmt) === 1062 ? 'Username Already Exist. Try another one' : 'Upload failed: ' . htmlspecialchars(mysqli_stmt_error($stmt));
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
            width: min(100%, 540px);
            margin: 0 auto;
        }
        .form-row { margin: 0 0 18px; text-align: left; }
        .form-row label { width: auto; display: block; text-align: left; }
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
                <form id="studentForm" action="#" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <label for="name">Username</label>
                        <input id="name" type="text" name="name" required pattern="[A-Za-z ]+">
                    </div>

                    <div class="form-row">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" required>
                    </div>

                    <div class="form-row">
                        <label for="phone">Phone</label>
                        <input id="phone" type="tel" name="phone" required pattern="(98|97)[0-9]{8}" title="Enter a 10-digit Nepali mobile number starting with 98 or 97." inputmode="numeric">
                    </div>


                    <div class="form-row">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required minlength="6">
                    </div>

                    <div class="form-row">
                        <label for="photo">Student Photo (JPG or PNG, up to 2 MB)</label>
                        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png" required>
                    </div>

                    <div>
                        <input type="submit" name="add_student">
                    </div>
                </form>
            </div>


    </div>
    </center>

    <script>
        document.getElementById("studentForm").addEventListener("submit", function(e) {

            let username = document.getElementsByName("name")[0].value.trim();
            let email = document.getElementsByName("email")[0].value.trim();
            let phone = document.getElementsByName("phone")[0].value.trim();
            let password = document.getElementsByName("password")[0].value.trim();

            // Username validation
            if (username === "") {
                alert("Please enter username.");
                e.preventDefault();
                return;
            }

            let usernamePattern = /^[A-Za-z\s]+$/;
            if (!usernamePattern.test(username)) {
                alert("Username should contain only letters.");
                e.preventDefault();
                return;
            }

            // Email validation
            if (email === "") {
                alert("Please enter email.");
                e.preventDefault();
                return;
            }

            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                alert("Invalid email address.");
                e.preventDefault();
                return;
            }

            // Phone validation
            if (phone === "") {
                alert("Please enter phone number.");
                e.preventDefault();
                return;
            }

            let phonePattern = /^(98|97)\d{8}$/;

            if (!phonePattern.test(phone)) {
                alert("Phone number must start with 98 or 97 and be exactly 10 digits.");
                e.preventDefault();
                return;
            }

            // Password validation
            if (password === "") {
                alert("Please enter password.");
                e.preventDefault();
                return;
            }

            
            let passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;


            if (!passwordPattern.test(password)) {
                alert("Password must be at least 6 characters and include an uppercase letter, a lowercase letter, a number, and a special character.");
                e.preventDefault();
                return;
            }

        });
    </script>

    <script src="../form-validation.js" defer></script>
</body>

</html>
