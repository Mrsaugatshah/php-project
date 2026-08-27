<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'student') {
    header('Location: ../login.php');
    exit;
}

$data = mysqli_connect('localhost', 'root', '', 'schoolproject');
if (!$data) {
    die('Database connection failed: ' . mysqli_connect_error());
}

$studentName = $_SESSION['username'];
$sql = "SELECT description, file, created_at FROM rusult WHERE student_name = ? OR student_name = 'All Students' ORDER BY created_at DESC";
$statement = mysqli_prepare($data, $sql);
mysqli_stmt_bind_param($statement, 's', $studentName);
mysqli_stmt_execute($statement);
$results = mysqli_stmt_get_result($statement);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results</title>
    <link rel="stylesheet" href="../admin/admin.css">
    <style>
        .result-table { width: 100%; max-width: 850px; margin: 20px auto; border-collapse: collapse; background: white; }
        .result-table th, .result-table td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        .result-table th { background: #eaf3ff; }
    </style>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>
    <div class="content">
        <h1>My Results</h1>

        <?php if (mysqli_num_rows($results) > 0) { ?>
            <table class="result-table">
                <tr><th>Description</th><th>Result File</th><th>Sent Date</th></tr>
                <?php while ($result = mysqli_fetch_assoc($results)) { ?>
                    <tr>
                        <td><?php echo nl2br(htmlspecialchars($result['description'])); ?></td>
                        <td>
                            <a href="../uploads/<?php echo rawurlencode(basename($result['file'])); ?>" download>
                                Download Result
                            </a>
                        </td>
                        <td><?php echo htmlspecialchars($result['created_at']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No result has been sent yet.</p>
        <?php } ?>
    </div>
</body>
</html>
