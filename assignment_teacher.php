<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'teacher') {
    header("Location: login.php");
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

$message = '';

if (isset($_POST['add_assignment'])) {
    $teacher = $_SESSION['username'];
    $student = isset($_POST['student']) ? $_POST['student'] : '';
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $deadline = isset($_POST['deadline']) ? $_POST['deadline'] : '';
    $fileName = '';

    if ($student === 'all') {
        $student = 'All Students';
    }

    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $allowed = ['pdf', 'doc', 'docx', 'zip'];
        $name = $_FILES['file']['name'];
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed, true)) {
            $folder = __DIR__ . '/uploads';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $safeName = time() . '_' . uniqid() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($name));
            $target = $folder . '/' . $safeName;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
                $fileName = $safeName;
            } else {
                $message = 'File upload failed. Try again.';
            }
        } else {
            $message = 'Only PDF, DOC, DOCX, or ZIP files are allowed.';
        }
    }

    if ($message === '') {
        $sql = "INSERT INTO assignment (teacher_name, student_name, title, description, deadline, file) VALUES ('$teacher', '$student', '$title', '$description', '$deadline', '$fileName')";

        if (mysqli_query($data, $sql)) {
            $message = 'Assignment uploaded successfully';
        } else {
            $message = 'Upload failed: ' . mysqli_error($data);
        }
    }
}

$studentQuery = "SELECT username FROM user WHERE usertype='student'";
$studentResult = mysqli_query($data, $studentQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <title>Teacher Assignment</title>
    <style>
        .content {
            text-align: center;
            margin-top: 50px;
        }

        form {
            display: inline-block;
            width: 420px;
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
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .message {
            margin-bottom: 15px;
            color: #006600;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <?php include 'teacher_sidebar.php'; ?>

    <div class="content">
        <h1>Assignment Dashboard</h1>
        <?php if ($message !== "") { ?>
            <div class="message"><?php echo $message; ?></div>
        <?php } ?>
        <form action="#" method="POST" enctype="multipart/form-data">
            <div>
                <label for="student">Student</label>
                <select name="student" id="student" required>
                    <option value="">Select student</option>
                    <option value="all">All Students</option>
                    <?php while ($studentInfo = mysqli_fetch_assoc($studentResult)) { ?>
                        <option value="<?php echo $studentInfo['username']; ?>"><?php echo $studentInfo['username']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div>
                <label for="title">Assignment Title</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div>
                <label for="description">Description</label>
                <textarea name="description" id="description" required></textarea>
            </div>

            <div>
                <label for="deadline">Deadline</label>
                <input type="date" name="deadline" id="deadline" required>
            </div>
            <div>
                <label for="file">file</label>
                <input type="file" name="file" id="file">
            </div>

            <div>
                <input type="submit" name="add_assignment" value="Send Assignment">
            </div>
        </form>
    </div>

</body>

</html>