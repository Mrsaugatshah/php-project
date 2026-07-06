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
                    <th style="padding:20px;font-size:15px">Name</th>
                    <th style="padding:20px;font-size:15px">Email</th>
                    <th style="padding:20px;font-size:15px">Phone</th>
                    <th style="padding:20px;font-size:15px"> Message</th>

                </tr>
                <?php
                while ($info = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td style="padding:20px"><?php echo $info['name']; ?></td>
                        <td style="padding:20px"><?php echo $info['email']; ?></td>
                        <td style="padding:20px"><?php echo $info['phone']; ?></td>
                        <td style="padding:20px"><?php echo $info['message']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>

        </center>
    </div>



</body>

</html>