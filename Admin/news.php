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

// Handle news submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_news'])) {
    $headline = $conn->real_escape_string($_POST['headline']);
    $content = $conn->real_escape_string($_POST['content']);
    $elaborative_news = $conn->real_escape_string($_POST['elaborative_news']);
    $date = $_POST['date'];

    // Handle image upload
    $target_dir = "uploads/news/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    $sql = "INSERT INTO news (image, headline, date, content, elaborative_news) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $target_file, $headline, $date, $content, $elaborative_news);
    $stmt->execute();
}

// Fetch existing news
$result = $conn->query("SELECT * FROM news ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management</title>
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
        .news-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .news-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .news-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .news-image {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 20px;
        }

        .news-content {
            display: flex;
            align-items: start;
        }

        .news-text {
            flex: 1;
        }
    </style>
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
        <div class="page-header animate-fade-in">
            <h1>News Management</h1>
        </div>

        <div class="news-form animate-fade-in">
            <h2>Add New Article</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Headline</label>
                    <input type="text" name="headline" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Short Content</label>
                    <textarea name="content" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Elaborative News</label>
                    <textarea name="elaborative_news" class="form-control" rows="5" required></textarea>
                </div>

                <button type="submit" name="add_news" class="btn">Add News</button>
            </form>
        </div>

        <div class="news-list">
            <h2>Existing News</h2>
            <?php while($row = $result->fetch_assoc()): ?>
            <div class="news-card animate-fade-in">
                <div class="news-content">
                    <div class="news-text">
                        <h3><?php echo htmlspecialchars($row['headline']); ?></h3>
                        <p class="date"><?php echo date('F d, Y', strtotime($row['date'])); ?></p>
                        <p><?php echo htmlspecialchars($row['content']); ?></p>
                        <button class="btn" onclick="location.href='edit_news.php?id=<?php echo $row['id']; ?>'">
                            Edit
                        </button>
                        <button class="btn btn-danger" onclick="deleteNews(<?php echo $row['id']; ?>)">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script>
        function deleteNews(id) {
            if (confirm('Are you sure you want to delete this news item?')) {
                fetch('delete_news.php?id=' + id, {method: 'POST'})
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            }
        }
    </script>
</body>
</html>