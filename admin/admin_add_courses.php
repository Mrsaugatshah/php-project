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
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";
if (isset($_POST['add_course'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $teacher_id = trim($_POST['teacher_id']);
    $duration = trim($_POST['duration']);

    $stmt = mysqli_prepare($conn, "INSERT INTO course (course_name, course_code, teacher_id, duration) VALUES (?, ?, ?, ?)");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssis", $course_name, $course_code, $teacher_id, $duration);
        if (mysqli_stmt_execute($stmt)) {
            $message = "Course added successfully.";
        } else {
            $message = "Error: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $message = "Error: " . mysqli_error($conn);
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
        <h1>ADD COURSES</h1>
        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form action="" method="post">
            <label>Course Name:</label>
            <input type="text" name="course_name" required><br><br>

            <label>Course Code</label>
            <input type="text" name="course_code"><br><br>

            <label>Teacher</label>
            <select name="teacher_id" required>
                <option value="">Select Teacher</option>
                <?php
                $sql = "SELECT id, name FROM teacher";
                $result = mysqli_query($conn, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                    }
                } else {
                    echo "<option value='' disabled>No teacher found</option>";
                }
                ?>
            </select><br><br>

            <label>Duration</label><br>
            <input type="text" name="duration" placeholder="e.g., 6 Months"><br><br>

            <input type="submit" name="add_course" value="Add Course">
        </form>
    </div>



</body>

</html>