<?php
error_reporting(0);
session_start();
session_destroy();

if($_SESSION['message'])
{
    $messagr=$_SESSION['message'];

    echo "<script type='text/javascript'>
    alert('$message');
    </script>";
}

$host="localhost";

$user="root";

$password="";

$db="school project";

$data=mysqli_connect($host,$user,$password,$db);
$sql="SELECT * FROM teacher";
$result=mysqli_query($data,$sql);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC SCHOOL</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</head>
<body style="background-color:cornsilk;">
    <nav>
        <label class="logo">ABC School</label>
        <ul>
            <li><a href=" ">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="#adm1">Admission</a></li>
            <li><a href="login.php" class="btn btn-success">Login</a></li>
        </ul>
    </nav>

    <div class="section1">
        <label class="img_text">We Teach Students With Care</label>
        <img class="main_img" src="class.jpg">
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img class="welcome_img" src="playground.jpg">
            </div>
            <div class="col-md-8">
                <h1>Welcome to ABC School</h1>
                <p class="sch_txt">ABC School is a premier educational institution committed to academic excellence and holistic development.
                    With a strong focus on innovation, creativity, and character-building, ABC School provides a nurturing 
                    environment for students to excel in academics, sports, and extracurricular activities. Our experienced faculty,
                    modern facilities, and student-centered approach ensure a well-rounded education that prepares students for a bright future.
                </p>
            </div>
        </div>
    </div>
    <center>
        <h1>Our Teachers</h1>
    </center>
    <div class="container">
        <div class="row">

            <?php

                while($info=$result->fetch_assoc())
                {
                    
        
            ?>

            <div class="col-md-4">
                
              <img class="teacher" src="<?php echo "{$info['image']}" ?>">
              <h3><?php echo "{$info['name']}"  ?></h3>
              <h5><?php echo "{$info['description']}"  ?></h5>

              <!-- <p>
              Mr. Nolen Davis (English Teacher) <br>
              A literature enthusiast, Mrs. Davis focuses on grammar, writing, and communication skills. She inspires students through storytelling and creative writing exercises.
              </p>   -->
            </div>

            <?php  
                } 
            ?>

        </div>
    </div>


    <center>
        <h1>Our Courses</h1>
    </center>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
              <img class="cources" src="web_development.jpg">
              <center>
              <h3>Web Developer</h3>
              </center>  
            </div>
            <div class="col-md-4">
                <img class="cources" src="graphic_design.jpg">
                <center>
                <h3>Graphic Design</h3>
                </center>
            </div>
            <div class="col-md-4">
                <img class="cources" src="digital_marketing.jpg">
                <center>
                <h3>Digital Marketing</h3>
                </center>
            </div>
        </div>
    </div>

    <div>
        <center>
            <h1 class="adm" id="adm1">
                Admission Form
            </h1>

        <div align="center" class="admission_form">
            <form action="data_check.php" method="post" >
                <div class="adm-int">
                    <label class="lavel_text">Name</label>
                    <input class="input_deg" type="text" name="name">
                </div>

                <div class="adm-int">
                    <label class="lavel_text">Email</label>
                    <input class="input_deg" type="text" name="email">
                </div>

                <div class="adm-int">
                    <label class="lavel_text">Phone</label>
                    <input class="input_deg" type="text" name="phone">
                </div>

                <div class="adm-int">
                    <label class="lavel_text">Message</label>
                    <textarea class="input_txt" name="message"></textarea>
                </div>
                <div class="adm-int">
                    <input class="btn btn-success" id="submit" type="submit" value="Apply" name="apply">
                </div>
                <br><br>
            </form>
        </div>
        </center>
    </div>

    <footer class="footer_text">
        <h3>All @copyright reserved by ABC School.</h3>
    </footer>
</body>
</html>