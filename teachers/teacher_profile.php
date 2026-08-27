<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'teacher') {
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

$teacherName = $_SESSION['username'];
$sql = "SELECT name, description, image FROM teacher WHERE name = ? LIMIT 1";
$statement = mysqli_prepare($data, $sql);
mysqli_stmt_bind_param($statement, 's', $teacherName);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$teacher = mysqli_fetch_assoc($result);

$teacherImage = '../img/teacher1.png';
if ($teacher && !empty($teacher['image'])) {
    $imageName = basename(str_replace('\\', '/', $teacher['image']));
    if ($imageName !== '' && is_file(__DIR__ . '/../image/' . $imageName)) {
        $teacherImage = '../image/' . $imageName;
    } elseif ($imageName !== '' && is_file(__DIR__ . '/../img/' . $imageName)) {
        $teacherImage = '../img/' . $imageName;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="../admin/admin.css">
    <style>
        .profile-card {
            max-width: 600px;
            margin: 35px auto;
            padding: 25px;
            text-align: center;
            background: white;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid skyblue;
        }

        .profile-details {
            margin-top: 20px;
            text-align: left;
        }

        .profile-details p {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
    </style>
</head>
<body>
    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <?php if ($teacher) { ?>
            <div class="profile-card">
                <img class="profile-image" src="<?php echo htmlspecialchars($teacherImage, ENT_QUOTES, 'UTF-8'); ?>" alt="Teacher photo">
                <h1><?php echo htmlspecialchars($teacher['name'], ENT_QUOTES, 'UTF-8'); ?></h1>

                <div class="profile-details">
                    <p><strong>Teacher Name:</strong> <?php echo htmlspecialchars($teacher['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>About Teacher:</strong> <?php echo htmlspecialchars($teacher['description'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        <?php } else { ?>
            <div class="profile-card">
                <h2>Teacher profile not found.</h2>
            </div>
        <?php } ?>
    </div>
</body>
</html>
