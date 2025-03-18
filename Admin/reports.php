<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";  // Default MySQL username
$password = "12345";      // Default MySQL password (empty for XAMPP)
$dbname = "department_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Add a new report (for admin/hod only)
if (isset($_POST['add_report'])) {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $category = $_POST['category'];
    $drive_link = $_POST['drive_link'];
    $date = $_POST['date']; // New date field
    
    $sql = "INSERT INTO reports (name, title, category, drive_link, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $title, $category, $drive_link, $date);
    
    if ($stmt->execute()) {
        $add_success = true;
    } else {
        $add_error = true;
    }
    $stmt->close();
}

// Delete a report (for admin/hod only)
if (isset($_POST['delete_report'])) {
    $report_id = $_POST['report_id'];
    
    $sql = "DELETE FROM reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $report_id);
    
    if ($stmt->execute()) {
        $delete_success = true;
    } else {
        $delete_error = true;
    }
    $stmt->close();
}

// Fetch all reports
$sql = "SELECT * FROM reports ORDER BY date DESC, created_at DESC";
$result = $conn->query($sql);

// Get categories for filter
$categories_sql = "SELECT DISTINCT category FROM reports ORDER BY category";
$categories_result = $conn->query($categories_sql);
$categories = [];
while($cat_row = $categories_result->fetch_assoc()) {
    $categories[] = $cat_row['category'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Reports Management</title>
    <style>
        @import url('https://rsms.me/inter/inter-ui.css');
        
        :root {
            --primary-color: #474A59;
            --secondary-color: #ff00ff;
            --accent-color: #ff0000;
            --text-light: #F1F1F2;
            --text-dark: #2D2F36;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter UI', sans-serif;
        }

        body {
            background: #f5f6fa;
            display: flex;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            height: 100vh;
            background: var(--primary-color);
            padding: 20px;
            color: var(--text-light);
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-info {
            padding: 20px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 30px;
        }

        .user-info h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .menu-item {
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .menu-item:hover {
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            transform: translateX(10px);
        }

        .menu-item i {
            width: 20px;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            padding: 30px;
            width: calc(100% - 280px);
            min-height: 100vh;
        }

        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        /* Animation Classes */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            outline: none;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 0.9em;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--secondary-color);
            color: var(--secondary-color);
        }

        .btn-outline:hover {
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
            color: white;
        }

        .report-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .report-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary-color);
        }
        
        .report-card:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .report-info {
            flex: 1;
        }
        
        .report-title {
            font-size: 1.2em;
            margin-bottom: 5px;
            color: var(--text-dark);
        }
        
        .report-meta {
            color: #666;
            font-size: 0.9em;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .report-actions {
            display: flex;
            gap: 10px;
        }

        .report-icon {
            margin-right: 5px;
            color: var(--accent-color);
        }

        .category-badge {
            background: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8em;
            color: #555;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: white;
        }

        .alert-success {
            background-color: #28a745;
        }

        .alert-danger {
            background-color: #dc3545;
        }

        .action-links {
            margin-top: 30px;
            display: flex;
            gap: 20px;
        }

        .action-link {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: all 0.3s ease;
            flex: 1;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: var(--text-dark);
        }

        .action-link:hover {
            transform: translateY(-10px);
        }

        .action-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--secondary-color), var(--accent-color));
        }

        .action-link i {
            font-size: 2.5em;
            margin-bottom: 15px;
            color: var(--secondary-color);
        }

        .action-link h3 {
            margin-bottom: 10px;
        }

        .action-link p {
            color: #666;
            font-size: 0.9em;
        }
        
        .delete-btn {
            color: var(--accent-color);
            cursor: pointer;
            transition: all 0.3s ease;
            background: none;
            border: none;
            font-size: 1.2em;
            padding: 5px;
        }

        .delete-btn:hover {
            transform: scale(1.2);
        }

        .filter-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="user-info">
        <h3><?php echo htmlspecialchars($username); ?></h3>
        <p>Role: <?php echo ucfirst(htmlspecialchars($role)); ?></p>
    </div>
    
    <div class="menu-item" onclick="location.href='dashboard.php'">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </div>
    
    <div class="menu-item" onclick="location.href='news.php'">
        <i class="fas fa-newspaper"></i>
        <span>News Management</span>
    </div>
    
    <div class="menu-item" onclick="location.href='research.php'">
        <i class="fas fa-book"></i>
        <span>Research Papers</span>
    </div>
    
    <?php if ($role === 'hod'): ?>
    <div class="menu-item" onclick="location.href='users.php'">
        <i class="fas fa-users"></i>
        <span>User Management</span>
    </div>
    <?php endif; ?>
    
    <div class="menu-item" onclick="location.href='reports.php'">
        <i class="fas fa-chart-bar"></i>
        <span>Reports</span>
    </div>
    
    <div class="menu-item" onclick="location.href='contacts.php'">
        <i class="fas fa-envelope"></i>
        <span>Contact Messages</span>
    </div>
    
    <div class="menu-item" onclick="location.href='logout.php'">
        <i class="fas fa-sign-out-alt"></i>
        <span>Logout</span>
    </div>
</div>

<div class="main-content animate-fade-in">
    <div class="page-header">
        <h2>Reports Management</h2>
        
        <a href="#add-report-form" class="btn btn-sm">
            <i class="fas fa-plus"></i> Add Report
        </a>
    </div>
    
    <?php if(isset($add_success)): ?>
        <div class="alert alert-success">
            Report added successfully!
        </div>
    <?php endif; ?>
    
    <?php if(isset($add_error)): ?>
        <div class="alert alert-danger">
            Error adding report. Please try again.
        </div>
    <?php endif; ?>
    
    <?php if(isset($delete_success)): ?>
        <div class="alert alert-success">
            Report deleted successfully!
        </div>
    <?php endif; ?>
    
    <?php if(isset($delete_error)): ?>
        <div class="alert alert-danger">
            Error deleting report. Please try again.
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-header">
            <h3>HOD Report Tools</h3>
        </div>
        
        <div class="action-links">
            <a href="https://docs.google.com/forms/d/1z6QFo_JBviPyfKfkY3bd5KONMUVlCzioWd79LUJl5yM/edit" class="action-link" target="_blank">
                <i class="fas fa-file-alt"></i>
                <h3>Create New Report</h3>
                <p>Submit the Google Form to generate a new department report</p>
            </a>
            
            <a href="https://drive.google.com/drive/folders/1lMnti3opPw8DD5J8JhE_I62dazWe5QLg" class="action-link" target="_blank">
                <i class="fab fa-google-drive"></i>
                <h3>Access Reports Drive</h3>
                <p>View and manage all reports in Google Drive</p>
            </a>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Existing Reports</h3>
            
            <?php if (count($categories) > 0): ?>
            <div class="filter-options">
                <select id="category-filter" class="form-control" style="width: auto; display: inline-block;">
                    <option value="">All Categories</option>
                    <?php foreach($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>"><?php echo htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="reports-container">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="report-card" data-category="<?php echo htmlspecialchars($row['category']); ?>">
                    <div class="report-info">
                        <h4 class="report-title"><?php echo htmlspecialchars($row['title']); ?></h4>
                        <div class="report-meta">
                            <span><i class="fas fa-file-alt report-icon"></i><?php echo htmlspecialchars($row['name']); ?></span>
                            <?php if (!empty($row['date'])): ?>
                            <span><i class="fas fa-calendar report-icon"></i><?php echo date('M d, Y', strtotime($row['date'])); ?></span>
                            <?php endif; ?>
                            <?php if (!empty($row['category'])): ?>
                            <span class="category-badge"><?php echo htmlspecialchars($row['category']); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="report-actions">
                        <a href="<?php echo htmlspecialchars($row['drive_link']); ?>" class="btn btn-sm" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View Report
                        </a>
                        
                        
                        <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this report?');">
                            <input type="hidden" name="report_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_report" class="delete-btn" title="Delete report">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card">
                    <p>No reports available.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
   
    <div class="card" id="add-report-form">
        <div class="card-header">
            <h3>Add Report to System</h3>
        </div>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Report Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="title">Report Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="date">Report Date</label>
                <input type="date" id="date" name="date" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control">
            </div>
            
            <div class="form-group">
                <label for="drive_link">Google Drive Link</label>
                <input type="url" id="drive_link" name="drive_link" class="form-control" required>
            </div>
            
            <button type="submit" name="add_report" class="btn">Add Report</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 500);
            });
        }, 3000);
        
        // Category filter functionality
        const categoryFilter = document.getElementById('category-filter');
        if (categoryFilter) {
            categoryFilter.addEventListener('change', function() {
                const selectedCategory = this.value;
                const reportCards = document.querySelectorAll('.report-card');
                
                reportCards.forEach(function(card) {
                    if (selectedCategory === '' || card.dataset.category === selectedCategory) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }
    });
</script>
</body>
</html>