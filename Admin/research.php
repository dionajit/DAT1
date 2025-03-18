<?php
session_start();
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);

$username = $_SESSION['username'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_paper'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $author = $conn->real_escape_string($_POST['author']);
        $date = $conn->real_escape_string($_POST['date']);
        $type = $conn->real_escape_string($_POST['type']);
        $abstract = $conn->real_escape_string($_POST['abstract']);
        $link = $conn->real_escape_string($_POST['link']);

        $sql = "INSERT INTO research_papers (title, author, date, type, abstract, link) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $title, $author, $date, $type, $abstract, $link);
        $stmt->execute();
    } elseif (isset($_POST['delete_paper']) && $_SESSION['role'] === 'hod') {
        $paper_id = intval($_POST['paper_id']);
        $conn->query("DELETE FROM research_papers WHERE id = $paper_id");
    }
}

$result = $conn->query("SELECT * FROM research_papers ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research Papers Management</title>
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
        
        .message-meta {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        
        .message-content {
            line-height: 1.6;
        }
        
        .timestamp {
            position: absolute;
            right: 20px;
            top: 20px;
            color: #999;
        }
    </style>
    <script>
        function confirmDelete(paperId) {
            if (confirm("Are you sure you want to delete this research paper?")) {
                document.getElementById("delete-form-" + paperId).submit();
            }
        }
    </script>
</head>
<body>
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
    
    <div class="main-content">
        <div class="page-header">
            <h1>Research Papers Management</h1>
        </div>

        <div class="paper-form">
            <h2>Add New Research Paper</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Author</label>
                    <input type="text" name="author" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <input type="text" name="type" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Abstract</label>
                    <textarea name="abstract" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label>Paper Link</label>
                    <input type="url" name="link" class="form-control" required>
                </div>

                <button type="submit" name="add_paper" class="btn">Add Paper</button>
            </form>
        </div>

        <div class="papers-list">
            <h2>Published Papers</h2>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="paper-card">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($row['author']); ?></p>
                <p><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($row['type']); ?></p>
                <p><strong>Abstract:</strong> <?php echo htmlspecialchars($row['abstract']); ?></p>
                <a href="<?php echo htmlspecialchars($row['link']); ?>" class="download-btn" target="_blank">
                    <i class="fas fa-link"></i> View Paper
                </a>
                <?php if ($_SESSION['role'] === 'hod'): ?>
                <form id="delete-form-<?php echo $row['id']; ?>" method="POST" style="display: inline;">
                    <input type="hidden" name="paper_id" value="<?php echo $row['id']; ?>">
                    <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                        Delete
                    </button>
                    <input type="hidden" name="delete_paper" value="1">
                </form>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
