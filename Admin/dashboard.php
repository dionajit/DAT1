<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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


    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header animate-fade-in">
            <h1>Welcome, <?php echo htmlspecialchars($username); ?></h1>
        </div>
        
        <div class="dashboard-stats">
            <?php
            // Connect to database
            $host = 'localhost';
            $db = 'department_portal';
            $user = 'root';
            $pass = '12345';
            
            $conn = new mysqli($host, $user, $pass, $db);
            
            // Get quick stats
            $news_count = $conn->query("SELECT COUNT(*) as count FROM news")->fetch_assoc()['count'];
            $papers_count = $conn->query("SELECT COUNT(*) as count FROM research_papers")->fetch_assoc()['count'];
            $messages_count = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
            ?>
            
            <div class="card animate-fade-in">
                <h3><i class="fas fa-newspaper"></i> News Articles</h3>
                <p class="stat"><?php echo $news_count; ?></p>
            </div>
            
            <div class="card animate-fade-in">
                <h3><i class="fas fa-book"></i> Research Papers</h3>
                <p class="stat"><?php echo $papers_count; ?></p>
            </div>
            
            <div class="card animate-fade-in">
                <h3><i class="fas fa-envelope"></i> Contact Messages</h3>
                <p class="stat"><?php echo $messages_count; ?></p>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
        // Add hover animation for menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('mouseenter', () => {
                anime({
                    targets: item,
                    translateX: 10,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
            
            item.addEventListener('mouseleave', () => {
                anime({
                    targets: item,
                    translateX: 0,
                    duration: 300,
                    easing: 'easeOutQuad'
                });
            });
        });
    </script>
</body>
</html>