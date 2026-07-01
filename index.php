<?php
error_reporting(0);
session_start();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    echo "<script type='text/javascript'>
    alert('$message');
    </script>";
    unset($_SESSION['message']);
}

$host = "localhost";
$user = "root";
$password = "";
$db = "schoolproject";

$data = mysqli_connect($host, $user, $password, $db);
if (!$data) {
    die("Database connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM teacher";
$result = mysqli_query($data, $sql);
if (!$result) {
    die("Query failed: " . mysqli_error($data));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <nav class="navbar">
        <label class="logo">w-school</label>
        <ul>
            <li><a href="">Home</a></li>
            <li><a href="">Contact</a></li>
            <li><a href="">Admission</a></li>
            <li><a href="login.php" class="login">Login</a></li>
        </ul>
    </nav>

    <div class="section1">
        <label class="img_text">We Teach Students With Care</label>
        <img class="main_img" src="img/school.png" alt="School campus image">
    </div>

    <div class="container">
        <div class="main-content">
            <div class="about-section">
                <img src="img\playground.jpg" class="img1">

                <div class="texo">
                    <h1>Welcome to W-school</h1>
                    <p>Welcome to W-School, a place where learning, creativity, and personal growth come together. Our mission is to provide quality education in a supportive and inspiring environment. We believe that every student has unique talents and potential, and we are committed to helping them achieve academic excellence and develop strong character.At W-School, we offer modern teaching methods, experienced teachers, and a wide range of educational and extracurricular activities. Our goal is to prepare students for future challenges by fostering critical thinking, leadership, and lifelong learning.Join W-School and become part of a community dedicated to knowledge, innovation, and success.</p>
                </div>

            </div>

            <div class="teacher-section">
                <h1>Our Teachers</h1>
                <div class="teacher-row">
                    <?php
                    while ($info = $result->fetch_assoc()) {

                    ?>
                        <div class="teacher-box">
                            <img class="teacher" src="<?php echo ($info['image']); ?>" alt="<?php echo ($info['name']); ?>">
                            <div class="teacher-info">
                                <h3><?php echo ($info['name']); ?></h3>
                                <h5><?php echo ($info['description']); ?></h5>
                            </div>
                        </div>
                    <?php

                    }

                    ?>

                </div>
            </div>
        </div>



        <div class="out-courses">
            <h1>Our Courses</h1>
            <div class="course-row">
                <div class="course-box">
                    <img class="course" src="img\web_development.png" alt="Web Development">
                    <div class="course-info">
                        <h3>Web Development</h3>
                        <p>Build modern websites and apps with HTML, CSS, and JavaScript.</p>
                    </div>
                </div>
                <div class="course-box">
                    <img class="course" src="img\graphic_design.png" alt="Graphic Design">
                    <div class="course-info">
                        <h3>Graphic Design</h3>
                        <p>Learn design principles and visual creativity for digital media.</p>
                    </div>
                </div>
                <div class="course-box">
                    <img class="course" src="img\digital_marketing.png" alt="Digital Marketing">
                    <div class="course-info">
                        <h3>Digital Marketing</h3>
                        <p>Master online marketing, social media, and brand promotion.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <center>
        <h1 class="admission-title">Admission Form</h1>
    </center>

    <div class="admission-form">
        <form action="data_check.php" method="POST">
            <div class="form-group">
                <label for="student-id">Id</label>
                <input id="student-id" type="text" name="id" placeholder="Enter your id">
                <label for="student-name">Name</label>
                <input id="student-name" type="text" name="name" placeholder="Enter your full name">
            </div>
            <div class="form-group">
                <label for="student-email">Email</label>
                <input id="student-email" type="email" name="email" placeholder="Enter your email address">
            </div>
            <div class="form-group">
                <label for="student-phone">Phone</label>
                <input id="student-phone" type="tel" name="phone" placeholder="Enter your phone no">
            </div>
            <div class="form-group">
                <label for="student-message">Message</label>
                <textarea id="student-message" name="message" placeholder="Tell us why you want to join"></textarea>
            </div>

            <div class="form-group form-action">
                <input type="submit" value="Submit Application" name="apply">
            </div>
        </form>
    </div>


    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-col about">
                <h4>W-School</h4>
                <p>W-School provides quality education with a caring approach. We focus on academic excellence, creativity, and character development.</p>
            </div>

            <div class="footer-col links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Admissions</a></li>
                    <li><a href="#">Courses</a></li>
                    <li><a href="#"></a></li>
                </ul>
            </div>

            <div class="footer-col contact">
                <h4>contact</h4>
                <p>123 Main Street, City</p>
                <p>Email: info@w-school.example</p>
                <p>Phone: +1 (555) 123-4567</p>
            </div>

            <div class="footer-col social">
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook">Facebook</a>
                    <a href="#" aria-label="Twitter">Twitter</a>
                    <a href="#" aria-label="Instagram">Instagram</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">&copy; 2026 W-School. All rights reserved.</div>
    </footer>

</body>

</html>