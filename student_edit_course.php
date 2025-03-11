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
$course_id = "";
$course_name = "";
$status = "";

if(isset($_GET['id'])) {
    $course_id = $_GET['id'];
    $student_username = $_SESSION['username'];
    
    $sql = "SELECT * FROM student_courses WHERE id='$course_id' AND student_username='$student_username'";
    $result = mysqli_query($conn, $sql);
    
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $course_name = $row['course_name'];
        $status = $row['status'];
    } else {
        header("location:student_view_courses.php");
        exit;
    }
}

if(isset($_POST['update'])) {
    $course_id = $_POST['course_id'];
    $new_course = $_POST['course'];
    $new_status = $_POST['status'];
    $student_username = $_SESSION['username'];
    
    // Check if student is already enrolled in the new course (if changing)
    if($new_course != $course_name) {
        $check_sql = "SELECT * FROM student_courses WHERE student_username='$student_username' AND course_name='$new_course'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if(mysqli_num_rows($check_result) > 0) {
            $message = "You are already enrolled in this course!";
        } else {
            $update_sql = "UPDATE student_courses SET course_name='$new_course', status='$new_status' WHERE id='$course_id' AND student_username='$student_username'";
            
            if(mysqli_query($conn, $update_sql)) {
                $message = "Course updated successfully!";
                $course_name = $new_course;
                $status = $new_status;
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        $update_sql = "UPDATE student_courses SET status='$new_status' WHERE id='$course_id' AND student_username='$student_username'";
        
        if(mysqli_query($conn, $update_sql)) {
            $message = "Course status updated successfully!";
            $status = $new_status;
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
    <title>Edit Course</title>
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
        <h1 class="page-header">Update Course</h1>
        
        <?php if(!empty($message)): ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="edit-course-form">
            <form action="" method="post">
                <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                
                <div class="form-group">
                    <label>Course:</label>
                    <select name="course" class="form-control" required>
                        <option value="Web Developer" <?php if($course_name == "Web Developer") echo "selected"; ?>>Web Developer</option>
                        <option value="Graphic Design" <?php if($course_name == "Graphic Design") echo "selected"; ?>>Graphic Design</option>
                        <option value="Digital Marketing" <?php if($course_name == "Digital Marketing") echo "selected"; ?>>Digital Marketing</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="active" <?php if($status == "active") echo "selected"; ?>>Active</option>
                        <option value="completed" <?php if($status == "completed") echo "selected"; ?>>Completed</option>
                        <option value="dropped" <?php if($status == "dropped") echo "selected"; ?>>Dropped</option>
                    </select>
                </div>
                
                <button type="submit" name="update" class="btn btn-primary">Update Course</button>
                <a href="student_view_courses.php" class="btn btn-default">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>