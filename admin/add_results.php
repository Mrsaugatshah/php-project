<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

$data = mysqli_connect('localhost', 'root', '', 'schoolproject');
if (!$data) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$message = '';

if (isset($_POST['add_result'])) {
    $studentName = $_POST['student_name'];
    $description = trim($_POST['description']);

    if (isset($_FILES['result_file']) && $_FILES['result_file']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['result_file']['name'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($extension !== 'pdf') {
            $message = 'Please upload a PDF result file.';
        } else {
            $folder = dirname(__DIR__) . '/uploads';
            if (!is_dir($folder)) {
                mkdir($folder, 0755, true);
            }

            $savedFileName = time() . '_' . basename($fileName);
            $filePath = $folder . '/' . $savedFileName;

            if (move_uploaded_file($_FILES['result_file']['tmp_name'], $filePath)) {
                $sql = 'INSERT INTO rusult (student_name, description, file) VALUES (?, ?, ?)';
                $statement = mysqli_prepare($data, $sql);
                mysqli_stmt_bind_param($statement, 'sss', $studentName, $description, $savedFileName);

                if (mysqli_stmt_execute($statement)) {
                    $message = 'Result sent successfully.';
                } else {
                    $message = 'Result could not be saved.';
                }
            } else {
                $message = 'File upload failed.';
            }
        }
    } else {
        $message = 'Please choose a result file.';
    }
}

$students = mysqli_query($data, "SELECT username FROM user WHERE usertype = 'student' ORDER BY username");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Result</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .result-form { max-width: 500px; margin: 25px auto; background: white; padding: 20px; }
        .result-form input, .result-form select, .result-form textarea { width: 100%; padding: 8px; margin: 5px 0 14px; }
        .result-form button { padding: 10px 16px; background: #2563eb; color: white; border: 0; cursor: pointer; }
        .message { text-align: center; color: green; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>
    <div class="content">
        <h1>Send Student Result</h1>
        <?php if ($message !== '') { ?><p class="message"><?php echo htmlspecialchars($message); ?></p><?php } ?>

        <form class="result-form" method="POST" enctype="multipart/form-data">
            <label>Student</label>
            <select name="student_name" required>
                <option value="">Select student</option>
                <option value="All Students">All Students</option>
                <?php while ($student = mysqli_fetch_assoc($students)) { ?>
                    <option value="<?php echo htmlspecialchars($student['username']); ?>">
                        <?php echo htmlspecialchars($student['username']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Description</label>
            <textarea name="description" required placeholder="Write result description"></textarea>

            <label>Result File (PDF)</label>
            <input type="file" name="result_file" accept="application/pdf" required>

            <button type="submit" name="add_result">Send Result</button>
        </form>
    </div>
    <script src="../form-validation.js" defer></script>
</body>
</html>
