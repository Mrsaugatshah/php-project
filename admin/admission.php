<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'admin') {
    header("Location: ../login.php");
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

$sql = "SELECT * FROM admission";
$result = mysqli_query($data, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($data));
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
    <?php
    include __DIR__ . '/admin_sidebar.php';



    ?>
    <div class="content">
        <center>
            <h1>Applied for Admission</h1>
            <table border="1px">
                <tr>
                    <th style="padding:20px;font-size:15px">Student ID</th>
                    <th style="padding:20px;font-size:15px">Photo</th>
                    <th style="padding:20px;font-size:15px">Name</th>
                    <th style="padding:20px;font-size:15px">Email</th>
                    <th style="padding:20px;font-size:15px">Phone</th>
                    <th style="padding:20px;font-size:15px"> Message</th>

                </tr>
                <?php
                while ($info = $result->fetch_assoc()) {
                    // Only the generated file name is stored; resolve it under
                    // /uploads/students and fall back to the admission list
                    // screenshot area when the file is missing.
                    $photoName = basename(str_replace('\\', '/', (string) ($info['image'] ?? '')));
                    $photoPath = dirname(__DIR__) . '/uploads/students/' . $photoName;
                    $hasPhoto = $photoName !== '' && is_file($photoPath);
                ?>
                    <tr>
                        <td style="padding:20px"><?php echo htmlspecialchars($info['student_id'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding:20px">
                            <?php if ($hasPhoto) { ?>
                            <a href="../uploads/students/<?php echo rawurlencode($photoName); ?>" target="_blank" rel="noopener">
                                <img src="../uploads/students/<?php echo rawurlencode($photoName); ?>" alt="Photo of <?php echo htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width:80px;height:80px;object-fit:cover;border-radius:10px;border:1px solid #cbd5e1;">
                            </a>
                            <?php } else { ?>
                            <span>—</span>
                            <?php } ?>
                        </td>
                        <td style="padding:20px"><?php echo htmlspecialchars($info['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding:20px"><?php echo htmlspecialchars($info['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding:20px"><?php echo htmlspecialchars($info['phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding:20px"><?php echo htmlspecialchars($info['message'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>

        </center>
    </div>



</body>

</html>