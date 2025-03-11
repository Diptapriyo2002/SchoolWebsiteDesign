<?php
session_start();

if(!isset($_SESSION["username"])) {
    header("location:login.php");
    exit;
} elseif($_SESSION['usertype']=='admin') {
    header('location:login.php');
    exit;
}

$host = "localhost";
$user = "root";
$password = "";
$db = "school project";

$conn = mysqli_connect($host, $user, $password, $db);

if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message = "";

if(isset($_POST['enroll'])) {
    $student_username = $_SESSION['username'];
    $course_name = $_POST['course'];
    
    // Check if student is already enrolled in this course
    $check_sql = "SELECT * FROM student_courses WHERE student_username='$student_username' AND course_name='$course_name'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if(mysqli_num_rows($check_result) > 0) {
        $message = "You are already enrolled in this course!";
    } else {
        $sql = "INSERT INTO student_courses (student_username, course_name) VALUES ('$student_username', '$course_name')";
        
        if(mysqli_query($conn, $sql)) {
            $message = "Course added successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Courses</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        .page-header 
        {
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
    <?php include 'student_sidebar.php'; ?>

    
    <div class="content">
        <h1 class="page-header">Add Courses</h1>
        
        <?php if(!empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="add-course-form">
            <form action="" method="post">
                <div class="form-group">
                    <label>Select Course:</label>
                    <select name="course" class="form-control" required>
                        <option value="">--Select a Course--</option>
                        <option value="Web Developer">Web Developer</option>
                        <option value="Graphic Design">Graphic Design</option>
                        <option value="Digital Marketing">Digital Marketing</option>
                    </select>
                </div>
                <button type="submit" name="enroll" class="btn btn-primary">Enroll</button>
            </form>
        </div>
    </div>
</body>
</html>