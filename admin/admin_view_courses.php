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
$conn = mysqli_connect($host, $user, $password, $db);
$sql = "SELECT c.*, t.name AS teacher_name, t.image AS teacher_image FROM course c LEFT JOIN teacher t ON c.teacher_id = t.id";
$result = mysqli_query($conn, $sql);





?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Admin homet</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f1f1f1;
        }

        img {
            margin-top: 5px;
            border-radius: 50%;
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
        <h1>View Courses</h1>
        <center>
            <h1>view All Courses Data</h1>
            <table border="1px">
                <tr>
                    <th class="table_th">Course_Name</th>
                    <th class="table_th">Course_Code</th>
                    <th class="table_th">Teacher</th>
                    <th calss="table_th">duration</th>


                </tr>

                <?php
                while ($info = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td class="table_td"><?php echo $info['course_name']; ?></td>
                        <td class="table_td"><?php echo $info['course_code']; ?></td>
                        <td class="table_td">
                            <?php echo $info['teacher_name']; ?><br>
                            <img src="<?php echo $info['teacher_image']; ?>" alt="Teacher Photo" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-top:5px;">
                        </td>
                        <td class="table_td"><?php echo $info['duration']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
        </center>


    </div>



</body>

</html>