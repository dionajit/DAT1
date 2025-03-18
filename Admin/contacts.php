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

// Handle message deletion
if (isset($_POST['delete_message']) && isset($_POST['message_id'])) {
    $message_id = $_POST['message_id'];
    
    // Prepare the delete statement to prevent SQL injection
    $delete_sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $message_id);
    
    if ($stmt->execute()) {
        // Success message
        $delete_success = true;
    } else {
        // Error message
        $delete_error = true;
    }
    $stmt->close();
}

// Fetch contact messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Contact Messages</title>
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
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .message-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .message-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: var(--secondary-color);
        }
        
        .message-card:hover {
            transform: translateX(10px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .message-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .message-meta {
            color: #666;
            font-size: 0.9em;
        }
        
        .message-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .message-content {
            line-height: 1.6;
            margin-top: 15px;
        }
        
        .timestamp {
            color: #999;
        }

        .message-actions {
            margin-left: 15px;
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
        <h2 class="page-header">Contact Messages</h2>
        
        <?php if(isset($delete_success)): ?>
            <div class="alert alert-success">
                Message deleted successfully!
            </div>
        <?php endif; ?>
        
        <?php if(isset($delete_error)): ?>
            <div class="alert alert-danger">
                Error deleting message. Please try again.
            </div>
        <?php endif; ?>
        
        <div class="messages-container">
            <?php if($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="message-card">
                    <div class="message-header">
                        <div class="message-meta">
                            <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            <span class="email">(<?php echo htmlspecialchars($row['email']); ?>)</span>
                        </div>
                        
                        <div class="message-info">
                            <span class="timestamp">
                                <?php echo date('M d, Y H:i', strtotime($row['created_at'])); ?>
                            </span>
                            <div class="message-actions">
                                <form method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                    <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_message" class="delete-btn" title="Delete message">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="message-content">
                        <?php echo nl2br(htmlspecialchars($row['message'])); ?>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="card">
                    <p>No messages found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-hide alerts after 3 seconds
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
        });
    </script>
</body>
</html>