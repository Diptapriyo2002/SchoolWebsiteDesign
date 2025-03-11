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


    if(isset($_POST['add_teacher']))
{
    $t_name=$_POST['name'];
    $t_description=$_POST['description'];
    $file=$_FILES['image']['name'];

    $dst="./image/".$file;

    $dst_db="image/".$file;
    move_uploaded_file($_FILES["image"]["tmp_name"], $dst);
    $sql="INSERT INTO teacher (name,description,image) VALUES ('$t_name','$t_description','$dst_db')";
    $result=mysqli_query($data,$sql);
        if($result)
        {
            header("location:admin_add_teacher.php");
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
    <?php

        include 'admin_css.php';
    ?>

<style type="text/css">

        .div_deg
        {
            background-color:skyblue;
            width: 500px;
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
</head>
<body>
    <?php

        include 'admin_sidebar.php';
    ?>

    <div class="content">
        <center>
        <h1 class="page-header">Add Teacher</h1>
        <br><br>
        <div class="div_deg">
            <form action="#" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="">Teacher Name</label>
                        <input type="text" name="name">
                    </div>
                    <br>
                    <div>
                        <label for="">Description</label>
                        <textarea name="description" id=""></textarea>
                    </div>
                    <br>
                    <div>
                        <label for="">Image</label>
                        <input type="file" name="image">
                    </div>
                    <br>
                    <div>
                        <input type="submit" class="btn btn-success" name="add_teacher" value="Add Teacher">
                    </div>
                    <br>
            </form>
        </div>
        </center>
    </div>

</body>
</html>