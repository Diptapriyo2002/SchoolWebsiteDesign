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

    // Database connection
    $host="localhost";
    $user="root";
    $password="";
    $db="school project";
    $data=mysqli_connect($host,$user,$password,$db);

    // Get counts for dashboard statistics
    $student_query = "SELECT COUNT(*) as student_count FROM user WHERE usertype='student'";
    $student_result = mysqli_query($data, $student_query);
    $student_count = mysqli_fetch_assoc($student_result)['student_count'];

    $teacher_query = "SELECT COUNT(*) as teacher_count FROM teacher";
    $teacher_result = mysqli_query($data, $teacher_query);
    $teacher_count = mysqli_fetch_assoc($teacher_result)['teacher_count'];

    $admission_query = "SELECT COUNT(*) as admission_count FROM admission";
    $admission_result = mysqli_query($data, $admission_query);
    $admission_count = mysqli_fetch_assoc($admission_result)['admission_count'];

    // Get recent admission requests
    $recent_admissions = "SELECT * FROM admission ORDER BY id DESC LIMIT 5";
    $recent_result = mysqli_query($data, $recent_admissions);
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
        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            width: 200px;
            text-align: center;
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-card h2 {
            font-size: 40px;
            margin: 10px 0;
            color: #424a5b;
        }
        
        .stat-card p {
            color: #777;
            font-size: 16px;
        }
        
        .blue-card {
            border-top: 4px solid #3498db;
        }
        
        .green-card {
            border-top: 4px solid #2ecc71;
        }
        
        .orange-card {
            border-top: 4px solid #e67e22;
        }
        
        .recent-admissions {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin: 20px;
        }
        
        .recent-admissions h3 {
            color: #424a5b;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .admission-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .admission-table th, .admission-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .welcome-message {
            background-color: skyblue;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 30px;
            color: #fff;
            text-align: center;
        }
        
        .welcome-message h1 {
            margin-bottom: 10px;
        }
        
        .quick-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 20px 0;
        }
        
        .quick-link {
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <?php
        include 'admin_sidebar.php';
    ?>

    <div class="content">
        <div class="welcome-message">
            <h1>Welcome to Admin Dashboard</h1>
            <p>Manage your school's data efficiently with our intuitive dashboard</p>
            
            <div class="quick-links">
                <a href="add_student.php" class="btn btn-primary quick-link">Add Student</a>
                <a href="admin_add_teacher.php" class="btn btn-success quick-link">Add Teacher</a>
                <a href="admission.php" class="btn btn-info quick-link">View Admissions</a>
            </div>
        </div>
        
        <div class="dashboard-stats">
            <div class="stat-card blue-card">
                <p>Total Students</p>
                <h2><?php echo $student_count; ?></h2>
                <a href="view_student.php" class="btn btn-primary btn-sm">View All</a>
            </div>
            
            <div class="stat-card green-card">
                <p>Total Teachers</p>
                <h2><?php echo $teacher_count; ?></h2>
                <a href="admin_view_teacher.php" class="btn btn-success btn-sm">View All</a>
            </div>
            
            <div class="stat-card orange-card">
                <p>Admission Requests</p>
                <h2><?php echo $admission_count; ?></h2>
                <a href="admission.php" class="btn btn-warning btn-sm">View All</a>
            </div>
        </div>
        
        <div class="recent-admissions">
            <h3>Recent Admission Requests</h3>
            <?php if(mysqli_num_rows($recent_result) > 0): ?>
                <table class="admission-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($recent_result)): ?>
                            <tr>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo substr($row['message'], 0, 50) . (strlen($row['message']) > 50 ? '...' : ''); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No recent admission requests.</p>
            <?php endif; ?>
            
            <div style="text-align: right; margin-top: 15px;">
                <a href="admission.php" class="btn btn-info">View All Requests</a>
            </div>
        </div>
    </div>
</body>
</html>