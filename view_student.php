<?php

error_reporting(0);
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

    $sql="SELECT * from user WHERE usertype='student'";
    $result=mysqli_query($data,$sql);
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
        .table_th
        {
            padding: 20px;
            font-size: 20px;
        }

        .table_td
        {
            padding: 20px;
            background-color: skyblue;
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
        <h1 class="page-header">Student Data</h1>

            <?php

                 if($_SESSION['message'])
                 {
                    echo $_SESSION['message'];
                 }

                 unset( $_SESSION['message']);

            ?>

        <br>
        
            <table border="1px">
                <tr>
                    <th class="table_th">UserName</th>
                    <th class="table_th">Email</th>
                    <th class="table_th">Phone</th>
                    <th class="table_th">Password</th>
                    <th class="table_th">Delete</th>
                    <th class="table_th">Update</th>
                </tr>


                <?php

                    while($info=$result->fetch_assoc())
                    {


                ?>
                <tr>
                <td class="table_td"><?php echo "{$info['username']}"; ?></td>
                <td class="table_td"><?php echo "{$info['email']}";   ?></td>
                <td class="table_td"><?php echo "{$info['phone']}";   ?></td>
                <td class="table_td"><?php echo "{$info['password']}";   ?></td>
                <td class="table_td"><?php echo "<a class='btn btn-danger' onclick=\" javascript:return confirm('Are your sure to delete?'); \" href='delete.php?student_id={$info['id']}'>Delete</onclick=>";?></td>
                <td class="table_td">
                    <?php echo "<a class='btn btn-success' href='update_student.php?student_id={$info['id']}'>Update</a>";   ?></td>
                </tr>

                <?php
                    }
                ?>
            </table>
        </center>

    </div>


</body>
</html>