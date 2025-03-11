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

// Get student details
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username='$username'";
$result = mysqli_query($conn, $sql);
$student_info = mysqli_fetch_assoc($result);

// Get enrolled courses count
$courses_sql = "SELECT COUNT(*) as total_courses FROM student_courses WHERE student_username='$username'";
$courses_result = mysqli_query($conn, $courses_sql);
$courses_count = mysqli_fetch_assoc($courses_result)['total_courses'];

// Get active courses
$active_sql = "SELECT COUNT(*) as active_courses FROM student_courses WHERE student_username='$username' AND status='active'";
$active_result = mysqli_query($conn, $active_sql);
$active_count = mysqli_fetch_assoc($active_result)['active_courses'];

// Get completed courses
$completed_sql = "SELECT COUNT(*) as completed_courses FROM student_courses WHERE student_username='$username' AND status='completed'";
$completed_result = mysqli_query($conn, $completed_sql);
$completed_count = mysqli_fetch_assoc($completed_result)['completed_courses'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <?php include 'admin_css.php'; ?>
    <style>
        .dashboard-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .welcome-section {
            background-color: #f8f9fa;
            padding: 30px;
            margin-bottom: 30px;
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            min-width: 200px;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .stat-card h2 {
            font-size: 36px;
            margin: 10px 0;
        }
        .card-blue {
            background-color: #007bff;
            color: white;
        }
        .card-green {
            background-color: #28a745;
            color: white;
        }
        .card-orange {
            background-color: #fd7e14;
            color: white;
        }
        .recent-activity {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>
    
    <div class="content">
        <div class="welcome-section">
            <h1>Welcome, <?php echo isset($student_info['name']) ? $student_info['name'] : $username; ?>!</h1>
            <p>This is your student dashboard where you can view and manage your courses and information.</p>
        </div>
        
        <div class="stats-container">
            <div class="stat-card card-blue">
                <h4>Total Courses</h4>
                <h2><?php echo $courses_count; ?></h2>
                <p>Enrolled Courses</p>
            </div>
            
            <div class="stat-card card-orange">
                <h4>Active Courses</h4>
                <h2><?php echo $active_count; ?></h2>
                <p>In Progress</p>
            </div>
            
            <div class="stat-card card-green">
                <h4>Completed</h4>
                <h2><?php echo $completed_count; ?></h2>
                <p>Courses Finished</p>
            </div>
        </div>
        
        <div class="dashboard-card">
            <h3>Student Information</h3>
            <table class="table">
                <tr>
                    <th width="30%">Username:</th>
                    <td><?php echo $username; ?></td>
                </tr>
                <?php if(isset($student_info['name'])): ?>
                <tr>
                    <th>Name:</th>
                    <td><?php echo $student_info['name']; ?></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($student_info['email'])): ?>
                <tr>
                    <th>Email:</th>
                    <td><?php echo $student_info['email']; ?></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($student_info['phone'])): ?>
                <tr>
                    <th>Phone:</th>
                    <td><?php echo $student_info['phone']; ?></td>
                </tr>
                <?php endif; ?>
                <?php if(isset($student_info['enrollment_date'])): ?>
                <tr>
                    <th>Enrolled Since:</th>
                    <td><?php echo $student_info['enrollment_date']; ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>
        
        <div class="dashboard-card recent-activity">
            <h3>Quick Actions</h3>
            <div class="list-group">
                <a href="student_add_courses.php" class="list-group-item list-group-item-action">
                    <i class="glyphicon glyphicon-plus"></i> Enroll in a New Course
                </a>
                <a href="student_view_courses.php" class="list-group-item list-group-item-action">
                    <i class="glyphicon glyphicon-list"></i> View My Courses
                </a>
                <a href="student_profile.php" class="list-group-item list-group-item-action">
                    <i class="glyphicon glyphicon-user"></i> Update My Profile
                </a>
            </div>
        </div>
        
        <?php
        // Get recent courses
        $recent_sql = "SELECT * FROM student_courses WHERE student_username='$username' ORDER BY enrollment_date DESC LIMIT 3";
        $recent_result = mysqli_query($conn, $recent_sql);
        
        if(mysqli_num_rows($recent_result) > 0):
        ?>
        <div class="dashboard-card">
            <h3>Recently Enrolled Courses</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($recent_result)): ?>
                    <tr>
                        <td><?php echo $row['course_name']; ?></td>
                        <td><?php echo $row['enrollment_date']; ?></td>
                        <td>
                            <?php if($row['status'] == 'active'): ?>
                                <span class="label label-primary"><?php echo $row['status']; ?></span>
                            <?php elseif($row['status'] == 'completed'): ?>
                                <span class="label label-success"><?php echo $row['status']; ?></span>
                            <?php else: ?>
                                <span class="label label-default"><?php echo $row['status']; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="student_view_courses.php" class="btn btn-default btn-sm">View All Courses</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>