<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check session variables
echo "<!-- Session Debug: " . print_r($_SESSION, true) . " -->";

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'student') {
    header("Location: ../login.php");
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);


$name = $_SESSION['username'];
$sql = "SELECT*FROM user WHERE username='$name' ";

$result = mysqli_query($data, $sql);

$info = mysqli_fetch_assoc($result);

if (isset($_POST['update_profile'])) {
    $s_phone = $_POST['phone'];
    $s_password = $_POST['password'];
    $s_email = $_POST['email'];

    $sql2 = "UPDATE user SET email='$s_email', phone='$s_phone', password='$s_password' WHERE username='$name'";

    $result2 = mysqli_query($data, $sql2);
    if ($result2) {
        header('Location: student_profile.php');
        exit;
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../admin/admin.css">
    <title>Admin homet</title>
    <style>
        .content {
            text-align: center;
            margin-top: 50px;
        }

        form {
            display: inline-block;
            width: 400px;
            padding: 20px;
            background: #f5f5f5;
        }

        form div {
            margin: 15px 0;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>


<body>

    <?php
    include 'student_sidebar.php'


    ?>
    <div class="content">

        <form id="profileForm" action="#" method="POST">
            <div>
                <label>name </label>
                <input type="text" name="name" value="<?php echo "{$info['username']}" ?>">

            </div>
            <div>
                <label>Email </label>
                <input type="text" name="email" value="<?php echo "{$info['email']}" ?>">

            </div>

            <div>
                <label>Phone</label>
                <input type="number" name="phone" value="<?php echo "{$info['phone']}" ?>">

            </div>

            <div>
                <label>password </label>
                <input type="text" name="password" value="<?php echo "{$info['password']}" ?>">

            </div>

            <div>

                <input type="submit" name="update_profile">

            </div>


        </form>




    </div>
    <script>
        document.getElementById("profileForm").addEventListener("submit", function(e) {

            let email = document.getElementsByName("email")[0].value.trim();
            let phone = document.getElementsByName("phone")[0].value.trim();
            let password = document.getElementsByName("password")[0].value.trim();

            // Email validation
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === "") {
                alert("Email is required.");
                e.preventDefault();
                return;
            }

            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                e.preventDefault();
                return;
            }

            // Phone validation
            let phonePattern = /^[0-9]{10}$/;
            if (phone === "") {
                alert("Phone number is required.");
                e.preventDefault();
                return;
            }

            if (!phonePattern.test(phone)) {
                alert("Phone number must contain exactly 10 digits.");
                e.preventDefault();
                return;
            }

            // Password validation
            if (password === "") {
                alert("Password is required.");
                e.preventDefault();
                return;
            }

            if (password.length < 6) {
                alert("Password must be at least 6 characters long.");
                e.preventDefault();
                return;
            }

        });
    </script>



</body>

</html>