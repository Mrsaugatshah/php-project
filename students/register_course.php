<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Check if user is logged in as student
if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'student') {
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

$student_username = $_SESSION['username'];
$message = "";

// Get student ID
$sql_student = "SELECT id FROM user WHERE username='$student_username' AND usertype='student'";
$result_student = mysqli_query($conn, $sql_student);
$student_data = mysqli_fetch_assoc($result_student);
$student_id = $student_data['id'] ?? null;

if (!$student_id) {
    die("Student not found");
}

// Handle course registration
if (isset($_POST['register_course'])) {
    $course_id = trim($_POST['course_id']);

    // Check if student already registered for this course
    $check_sql = "SELECT * FROM student_course WHERE student_id=$student_id AND course_id=$course_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "You are already registered for this course!";
    } else {
        $register_sql = "INSERT INTO student_course (student_id, course_id, registration_date) VALUES ($student_id, $course_id, NOW())";
        if (mysqli_query($conn, $register_sql)) {
            $message = "Successfully registered for the course!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

// Handle course unregistration
if (isset($_POST['unregister_course'])) {
    $course_id = trim($_POST['course_id']);
    $delete_sql = "DELETE FROM student_course WHERE student_id=$student_id AND course_id=$course_id";
    if (mysqli_query($conn, $delete_sql)) {
        $message = "Successfully unregistered from the course!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

// Get all courses with teacher information
$sql_courses = "SELECT c.*, t.name AS teacher_name FROM course c LEFT JOIN teacher t ON c.teacher_id = t.id";
$result_courses = mysqli_query($conn, $sql_courses);

// Get student's registered courses
$sql_registered = "SELECT course_id FROM student_course WHERE student_id=$student_id";
$result_registered = mysqli_query($conn, $sql_registered);
$registered_courses = array();
while ($row = mysqli_fetch_assoc($result_registered)) {
    $registered_courses[] = $row['course_id'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../admin/admin.css">
    <title>Register Courses</title>
    <style>
        .content {
            padding: 20px;
        }

        .message {
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #4CAF50;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        button {
            padding: 8px 15px;
            margin: 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .register-btn {
            background-color: #4CAF50;
            color: white;
        }

        .register-btn:hover {
            background-color: #45a049;
        }

        .unregister-btn {
            background-color: #f44336;
            color: white;
        }

        .unregister-btn:hover {
            background-color: #da190b;
        }

        .course-status {
            font-weight: bold;
            color: #4CAF50;
        }

        .no-courses {
            padding: 20px;
            text-align: center;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header class="header">
        <a href="student.home.php">Student Dashboard</a>
        <div class="logout">
            <a href="student_profile.php">Profile</a>
            <a href="../logout.php">Logout</a>
        </div>
    </header>

    <aside>
        <ul>
            <li><a href="student.home.php">Home</a></li>
            <li><a href="student_profile.php">Profile</a></li>
            <li><a href="register_course.php">Register Courses</a></li>
            <li><a href="view_student.php">View Details</a></li>
            <li><a href="assignment_student.php">Assignments</a></li>
        </ul>
    </aside>

    <div class="content">
        <h1>Course Registration</h1>

        <?php if (!empty($message)): ?>
            <div class="message <?php echo (strpos($message, 'Error') !== false || strpos($message, 'already') !== false) ? 'error' : 'success'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <h2>Available Courses</h2>

        <?php
        if (mysqli_num_rows($result_courses) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>Course Name</th>";
            echo "<th>Course Code</th>";
            echo "<th>Teacher</th>";
            echo "<th>Duration</th>";
            echo "<th>Status</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            while ($course = mysqli_fetch_assoc($result_courses)) {
                $is_registered = in_array($course['id'], $registered_courses);
                echo "<tr>";
                echo "<td>" . htmlspecialchars($course['course_name']) . "</td>";
                echo "<td>" . htmlspecialchars($course['course_code']) . "</td>";
                echo "<td>" . htmlspecialchars($course['teacher_name'] ?? 'Not Assigned') . "</td>";
                echo "<td>" . htmlspecialchars($course['duration'] ?? 'N/A') . "</td>";
                echo "<td>";
                if ($is_registered) {
                    echo "<span class='course-status'>✓ Registered</span>";
                } else {
                    echo "<span>Not Registered</span>";
                }
                echo "</td>";
                echo "<td>";
                if ($is_registered) {
                    echo "<form method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='course_id' value='" . $course['id'] . "'>";
                    echo "<button type='submit' name='unregister_course' class='unregister-btn'>Unregister</button>";
                    echo "</form>";
                } else {
                    echo "<form method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='course_id' value='" . $course['id'] . "'>";
                    echo "<button type='submit' name='register_course' class='register-btn'>Register</button>";
                    echo "</form>";
                }
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='no-courses'>No courses available at the moment.</div>";
        }
        ?>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>