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

$student_username = $_SESSION['username'];
$sql = "SELECT * FROM student_courses WHERE student_username='$student_username'";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Courses</title>
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
        <h1 class="page-header">My Courses</h1>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['course_name']; ?></td>
                            <td><?php echo $row['enrollment_date']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <a href="student_edit_course.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                You are not enrolled in any courses yet. <a href="student_add_courses.php">Enroll now</a>.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>