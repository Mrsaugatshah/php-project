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

        .profile-photo {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.18);
        }

        label {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>


<body>

    <?php
    include 'student_sidebar.php'


    ?>
    <div class="content">

        <?php
        $photoName = basename(str_replace('\\', '/', (string) ($info['image'] ?? '')));
        $photoFile = dirname(__DIR__) . '/uploads/students/' . $photoName;
        if ($photoName !== '' && is_file($photoFile)) :
        ?>
            <div class="profile-photo-wrap">
                <img class="profile-photo" src="../uploads/students/<?php echo rawurlencode($photoName); ?>" alt="Profile photo of <?php echo htmlspecialchars($info['username'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
        <?php else : ?>
            <p>No profile photo available.</p>
        <?php endif; ?>

        <form id="profileForm" action="#" method="POST">
            <div>
                <label>name </label>
                <input type="text" name="name" value="<?php echo "{$info['username']}" ?>" readonly>

            </div>
            <div>
                <label>Email </label>
                <input type="email" name="email" value="<?php echo "{$info['email']}" ?>" required>

            </div>

            <div>
                <label>Phone</label>
                <input type="tel" name="phone" value="<?php echo "{$info['phone']}" ?>" required pattern="(98|97)[0-9]{8}" title="Enter a 10-digit Nepali mobile number starting with 98 or 97." inputmode="numeric">

            </div>

            <div>
                <label>password </label>
                <input type="password" name="password" value="<?php echo "{$info['password']}" ?>" required minlength="6">

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
            let phonePattern = /^(98|97)[0-9]{8}$/;
            if (phone === "") {
                alert("Phone number is required.");
                e.preventDefault();
                return;
            }

            if (!phonePattern.test(phone)) {
                alert("Enter a 10-digit Nepali mobile number starting with 98 or 97.");
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



    <script src="../form-validation.js" defer></script>
</body>

</html>
