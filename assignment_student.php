<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check session variables
echo "<!-- Session Debug: " . print_r($_SESSION, true) . " -->";

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'student') {
    header("Location: login.php");
    exit;
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
        .content {
            margin: 40px auto;
            max-width: 1000px;
            padding: 0 16px;
            font-family: Arial, Helvetica, sans-serif;
            color: #333;
        }

        h1 {
            margin-bottom: 18px;
            font-size: 26px;
            color: #222;
        }

        .assignment-table {
            width: 100%;
            margin: 20px auto 0;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .assignment-table th,
        .assignment-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }

        .assignment-table th {
            background: #f3f4f6;
            font-weight: 700;
        }

        .assignment-table tr:hover td {
            background: #f9fafb;
        }

        .download-link {
            color: #0b66c3;
            text-decoration: none;
            font-weight: 600;
        }

        .empty-note {
            margin-top: 18px;
            color: #666;
        }
    </style>
</head>


<body>

    <?php
    include 'student_sidebar.php';

    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "schoolproject";

    $conn = mysqli_connect($host, $user, $password, $db);
    if (!$conn) {
        echo "<p>Database connection failed: " . mysqli_connect_error() . "</p>";
    } else {
        $student = $_SESSION['username'];
        $sql = "SELECT * FROM assignment WHERE student_name = '$student' OR student_name = 'All Students' ORDER BY deadline DESC";
        $res = mysqli_query($conn, $sql);
        if ($res) {
            $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);
        } else {
            $rows = [];
            $query_error = mysqli_error($conn);
        }
    }
    ?>

    <div class="content">
        <h1>Assignment Dashboard</h1>
        <?php if (isset($_SESSION['submit_msg'])) { ?>
            <div style="margin-bottom:12px;color:#155724;background:#d4edda;padding:8px;border-radius:4px;display:inline-block;">
                <?php echo $_SESSION['submit_msg'];
                unset($_SESSION['submit_msg']); ?>
            </div>
        <?php } ?>
        <?php if (!empty($rows)) { ?>

            <table class="assignment-table">
                <tr>
                    <th>Teacher</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>File</th>
                </tr>
                <?php foreach ($rows as $row) { ?>
                    <tr>
                        <td><?php echo $row['teacher_name']; ?></td>
                        <td><?php echo $row['title']; ?></td>
                        <td><?php echo nl2br($row['description']); ?></td>
                        <td><?php echo !empty($row['deadline']) ? date('d M Y', strtotime($row['deadline'])) : '—'; ?></td>
                        <td>
                            <?php
                            if (!empty($row['file'])) {
                                $fileName = basename($row['file']);
                                $uploadPath = __DIR__ . '/uploads/' . $fileName;
                                if (file_exists($uploadPath)) {
                                    $fileHref = 'uploads/' . rawurlencode($fileName);
                                    echo '<a class="download-link" href="' . $fileHref . '" target="_blank" download>' . $fileName . '</a>';
                                } else {
                                    echo 'File not found';
                                }
                            } else {
                                echo '—';
                            }
                            ?>
                        </td>

                    </tr>
                <?php } ?>
            </table>
        <?php
        } else {
            if (!empty($query_error)) {
                echo '<p class="empty-note">Query error: ' . htmlspecialchars($query_error) . '</p>';
            } else {
                echo '<p class="empty-note">No assignments found for <strong>' . htmlspecialchars($_SESSION['username']) . '</strong>.</p>';
            }
        }


        if (!empty($_GET['debug'])) {
            echo '<h3>Debug — fetched rows</h3><pre>' . htmlspecialchars(print_r($rows, true)) . '</pre>';
        }
        ?>


</body>

</html>