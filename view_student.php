<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: login.php');
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

$sql = "SELECT * FROM user WHERE usertype='student'";
$result = mysqli_query($data, $sql);
if (!$result) {
    die('Query failed: ' . mysqli_error($data));
}




?>

<!DOCTYPE html>
<html lang="en">


<head>
    <style type="text/css">
        .table_th {
            padding: 20px;
            font-size: 20px;


        }

        .table_td {
            padding: 20px;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Admin homet</title>
</head>


<body>
    <header class="header">
        <a href=" ">Student Data</a>
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
        <h1>Student Data</h1>
        <?php
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }


        ?>
        <table border="1px">
            <tr>
                <th class="table_th">Username</th>
                <th class="table_th">Email</th>
                <th class="table_th">Phone</th>
                <th class="table_th">Password</th>
                <th class="table_th">Delete</th>
                <th class="table_th">Update</th>
            </tr>
            <?php if (mysqli_num_rows($result) > 0) : ?>
                <?php while ($info = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td class="table_td"><?php echo "{$info['username']}"; ?></td>
                        <td class="table_td"><?php echo "{$info['email']}"; ?> </td>

                        <td class="table_td"><?php echo "{$info['phone']}"; ?></td>
                        <td class="table_td"><?php echo "{$info['password']}"; ?></td>

                        <td class="table_td"><?php echo "<a onClick=\"javascript:return confirm('Are you sure to delete it')\" href='delete.php? student_id={$info['id']}'>Delete</a>"; ?> </td>
                        <td class="table_td"><?php echo "<a href='update.php?student_id={$info['id']}'>Update</a>"; ?> </td>

                    </tr>
                <?php endwhile; ?>
                </tr>

            <?php else : ?>
                <tr>
                    <td class="table_td" colspan="5" style="text-align:center;">No student records found.</td>
                </tr>
            <?php endif; ?>
        </table>


    </div>



</body>

</html>