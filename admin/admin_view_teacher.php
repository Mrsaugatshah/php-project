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

if (isset($_GET['teacher_id']) && $_GET['teacher_id'] !== '') {
    $t_id = intval($_GET['teacher_id']);
    $sql2 = "DELETE FROM teacher WHERE id='$t_id'";
    $result2 = mysqli_query($data, $sql2);
    if ($result2) {
        header('Location: ' . $_SERVER['PHP_SELF']);
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
        .table_th {
            padding: 20px;
            font-size: 20px;
        }

        .table_td {
            padding: 20px;
            background-color: skyblue;
        }

        .btn.del {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px;
            background-color: red;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-upd {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px;
            background-color: green;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;

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
            <h1>view All Teacher Data</h1>
            <table border="1px">
                <tr>
                    <th class="table_th">Teacher Name</th>
                    <th class="table_th">About Teacher</th>
                    <th class="table_th">Image</th>
                    <th calss="table_th">Password</th>
                    <th class="table_th">Delete</th>
                    <th class="table_th">Update</th>

                </tr>

                <?php
                while ($info = $result->fetch_assoc()) {
                    $teacher_image = $info['image'];
                ?>
                    <tr>
                        <td class="table_td"><?php echo "{$info['name']}" ?></td>
                        <td class="table_td"><?php echo "{$info['description']}" ?></td>
                        <td class="table_td">
                            <img height="100px" width="100px" src="<?php echo $teacher_image; ?>" alt="Teacher Image">
                        </td>
                        <td class="table_td"><?php echo "{$info['password']}" ?>

                        </td>
                        <td class="table_td">
                            <?php
                            echo "<a onclick=\"return confirm('Are You Sure To Delete This?');\" class='btn del' href='admin_view_teacher.php?teacher_id={$info['id']}'>Delete</a>";
                            ?>
                        </td>

                        <td class="table_td">
                            <?php
                            echo "<a class='btn-upd' href='admin_update_teacher.php?teacher_id={$info['id']}'>Update</a>";
                            ?>
                        </td>

                    </tr>
                <?php
                }
                ?>
            </table>
        </center>


    </div>



</body>

</html>