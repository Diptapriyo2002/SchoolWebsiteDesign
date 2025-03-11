<?php
session_start();

    if(!isset($_SESSION["username"]))
    {
        header("location:login.php");
    }

    elseif($_SESSION['usertype']=='student')
    {
        header('location:login.php');
    }

$host="localhost";

$user="root";

$password="";

$db="school project";

$data=mysqli_connect($host,$user,$password,$db);

if(isset($_POST['add_student']))
{
    $username=$_POST['name'];
    $user_email=$_POST['email'];
    $user_phone=$_POST['phone'];
    $user_password=$_POST['password'];
    $user_type="student";

    $check="SELECT * FROM user WHERE username='$username'";
    $check_user=mysqli_query($data,$check);


    $row_count=mysqli_num_rows($check_user);
    if($row_count==1)
    {
        echo "<script type='text/javascript'>
        alert('Username already exists! Try another one');
        </script>";
    }
    else
    {

    $sql="INSERT INTO user(username,email,phone,password,usertype) 
    VALUES ('$username','$user_email','$user_phone','$user_password','$user_type')";

    $result=mysqli_query($data,$sql);

    if($result)
    {
        echo "<script type='text/javascript'>
        alert('Data Uploaded Success');
        </script>";
    }

    else
    {
        echo "Upload Failed";
    }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <style type="text/css">
        label{
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .div_deg
        {
            background-color: skyblue;
            width: 400px;
            padding-top: 70px;
            padding-bottom: 70px;
        }
        .page-header {
            background-color: #f8f9fa;
            padding: 15px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
    <?php

        include 'admin_css.php';
    ?>
</head>
<body>
    <?php

        include 'admin_sidebar.php';
    ?>

    <div class="content">
        <center>
        <h1 class="page-header">Add Student</h1>

            <div class="div_deg">
                <form action="#" method="post">
                    <div>
                        <label for="">Username</label>
                        <input type="text" name="name">
                    </div>

                    <div>
                        <label for="">Email</label>
                        <input type="email" name="email">
                    </div>

                    <div>
                        <label for="">Phone</label>
                        <input type="number" name="phone">
                    </div>

                    <div>
                        <label for="">Password</label>
                        <input type="text" name="password">
                    </div>

                    <div>
                        <input type="submit" class="btn btn-primary" name="add_student" value="Add Student">
                    </div>
                </form>
            </div>
        </center>
    </div>

    
</body>
</html>