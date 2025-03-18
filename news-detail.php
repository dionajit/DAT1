<?php
$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is set and is a number
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Prepare the query with a parameter to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $news = $result->fetch_assoc();
    } else {
        // Redirect to the all news page if news item not found
        header("Location: all-news.php");
        exit();
    }
} else {
    // Redirect to the all news page if no ID or invalid ID
    header("Location: all-news.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($news['headline']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-image: linear-gradient(rgba(4,9,30,0.7),rgba(4,9,30,0.7)),url(images2/university.jpg);
            background-position: center;
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
        }
        
        .news-detail-container {
            max-width: 900px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
        }
        
        .back-button {
            display: inline-block;
            margin-bottom: 30px;
            padding: 10px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .back-button:hover {
            background: #555;
        }
        
        .news-detail-date {
            font-size: 1rem;
            color: #00ccff;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        
        .news-detail-headline {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #333;
            line-height: 1.3;
        }
        
        .news-detail-content {
            font-size: 1.1rem;
            color: #444;
            line-height: 1.8;
            margin-bottom: 40px;
        }
        
        .news-detail-elaborative {
            font-size: 1.1rem;
            color: #444;
            line-height: 1.8;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .social-share {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .social-share h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
            color: #333;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background: #f5f5f5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: #333;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        
        .social-icon:hover {
            background: #e5e5e5;
            transform: translateY(-3px);
        }
        
        @media (max-width: 768px) {
            .news-detail-container {
                margin: 30px 15px;
                padding: 20px;
            }
            
            .news-detail-headline {
                font-size: 2rem;
            }
            
            .news-detail-content,
            .news-detail-elaborative {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="news-detail-container">
        <a href="all-news.php" class="back-button">← Back to All News</a>
        
        <div class="news-detail-date"><?php echo strtoupper(date('d M Y', strtotime($news['date']))); ?></div>
        <h1 class="news-detail-headline"><?php echo htmlspecialchars($news['headline']); ?></h1>
        
        <div class="news-detail-content">
            <?php echo nl2br(htmlspecialchars($news['content'])); ?>
        </div>
        
        <div class="news-detail-elaborative">
            <?php echo nl2br(htmlspecialchars($news['elaborative_news'])); ?>
        </div>
        
    </div>
</body>
</html>
