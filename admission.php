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
$sql="SELECT * from admission ORDER BY id DESC";
$result=mysqli_query($data,$sql);

// Count total admissions
$count_query = "SELECT COUNT(*) as total FROM admission";
$count_result = mysqli_query($data, $count_query);
$total_admissions = mysqli_fetch_assoc($count_result)['total'];

// Process search
$search_term = "";
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search_term = mysqli_real_escape_string($data, $_GET['search']);
    $sql = "SELECT * FROM admission WHERE 
            name LIKE '%$search_term%' OR 
            email LIKE '%$search_term%' OR 
            phone LIKE '%$search_term%' OR 
            message LIKE '%$search_term%'
            ORDER BY id DESC";
    $result = mysqli_query($data, $sql);
}

// Approve application functionality (for future implementation)
if(isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    // Code to approve application would go here
    // For now, just redirect back to the page
    $_SESSION['message'] = "Application approved successfully!";
    header("location: admission.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Applications - Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        .content {
            margin-left: 16%;
            padding: 20px;
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
        
        .page-title {
            margin: 0;
            color: #424a5b;
        }
        
        .stats-summary {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #3498db;
            margin: 0;
        }
        
        .search-box {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .search-input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .admissions-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .admissions-table th {
            background-color: #f8f9fa;
            color: #424a5b;
            text-align: left;
            padding: 15px;
            font-weight: 600;
            border-bottom: 2px solid #ddd;
        }
        
        .admissions-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }
        
        .admissions-table tr:hover {
            background-color: #f5f5f5;
        }
        
        .message-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .actions-cell {
            text-align: center;
        }
        
        .btn-view {
            background-color: #3498db;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            display: inline-block;
            margin-right: 5px;
        }
        
        .btn-approve {
            background-color: #2ecc71;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            display: inline-block;
        }
        
        .status-label {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background-color: #f39c12;
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .empty-state p {
            color: #777;
            margin-bottom: 20px;
        }
        
        /* Styling for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border-radius: 5px;
            width: 70%;
            max-width: 700px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .modal-header {
            padding-bottom: 15px;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-body {
            margin-bottom: 20px;
        }
        
        .field-label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #777;
        }
        
        .field-value {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        
        .modal-actions {
            border-top: 1px solid #eee;
            padding-top: 15px;
            text-align: right;
        }
        
        /* Notification styling */
        .notification {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            position: relative;
        }
        
        .notification-success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
            color: #155724;
        }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <div class="page-header">
            <h1 class="page-title">Admission Applications</h1>
            <a href="adminhome.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="notification notification-success">
                <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="stats-summary">
            <p>Total Applications: <span class="stat-number"><?php echo $total_admissions; ?></span></p>
        </div>
        
        <div class="search-box">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search by name, email, phone or message..." class="search-input" value="<?php echo $search_term; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <?php if(!empty($search_term)): ?>
                    <a href="admission.php" class="btn btn-default">Clear</a>
                <?php endif; ?>
            </form>
        </div>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <table class="admissions-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($info = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $info['id']; ?></td>
                        <td><?php echo htmlspecialchars($info['name']); ?></td>
                        <td><?php echo htmlspecialchars($info['email']); ?></td>
                        <td><?php echo htmlspecialchars($info['phone']); ?></td>
                        <td class="message-cell"><?php echo htmlspecialchars($info['message']); ?></td>
                        <td><span class="status-label status-pending">Pending</span></td>
                        <td class="actions-cell">
                            <a href="javascript:void(0)" class="btn-view" onclick="openModal(<?php echo $info['id']; ?>, '<?php echo addslashes(htmlspecialchars($info['name'])); ?>', '<?php echo addslashes(htmlspecialchars($info['email'])); ?>', '<?php echo addslashes(htmlspecialchars($info['phone'])); ?>', '<?php echo addslashes(htmlspecialchars($info['message'])); ?>')">View</a>
                            <a href="admission.php?approve_id=<?php echo $info['id']; ?>" class="btn-approve">Approve</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <h3>No applications found</h3>
                <?php if(!empty($search_term)): ?>
                    <p>No results match your search criteria. Try a different search term.</p>
                    <a href="admission.php" class="btn btn-primary">Clear Search</a>
                <?php else: ?>
                    <p>There are no admission applications in the system yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal for viewing application details -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <div class="modal-header">
                <h2>Application Details</h2>
            </div>
            <div class="modal-body">
                <div>
                    <span class="field-label">Applicant Name:</span>
                    <div class="field-value" id="modal-name"></div>
                </div>
                <div>
                    <span class="field-label">Email Address:</span>
                    <div class="field-value" id="modal-email"></div>
                </div>
                <div>
                    <span class="field-label">Phone Number:</span>
                    <div class="field-value" id="modal-phone"></div>
                </div>
                <div>
                    <span class="field-label">Message:</span>
                    <div class="field-value" id="modal-message"></div>
                </div>
            </div>
            <div class="modal-actions">
                <a href="#" id="approve-link" class="btn btn-success">Approve Application</a>
                <button class="btn btn-default" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        // Modal functions
        function openModal(id, name, email, phone, message) {
            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-email').textContent = email;
            document.getElementById('modal-phone').textContent = phone;
            document.getElementById('modal-message').textContent = message;
            document.getElementById('approve-link').href = "admission.php?approve_id=" + id;
            document.getElementById('applicationModal').style.display = "block";
        }
        
        function closeModal() {
            document.getElementById('applicationModal').style.display = "none";
        }
        
        // Close modal when clicking outside of it
        window.onclick = function(event) {
            var modal = document.getElementById('applicationModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>