<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Debug: Check session variables
echo "<!-- Session Debug: " . print_r($_SESSION, true) . " -->";

if (!isset($_SESSION['username']) || $_SESSION['usertype'] !== 'teacher') {
    header("Location: ../login.php");
    exit;
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../admin/admin.css">
    <title>Admin homet</title>
    <style>
        .content {
            text-align: center;
            margin-top: 50px;
        }

        form {
            display: inline-block;
            width: 400px;
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
        input[type="number"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>


<body>

    <?php
    include 'teacher_sidebar.php'


    ?>
    <div class="content">





</body>

</html>