<?php
$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * FROM news ORDER BY date DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Department News</title>
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
            position: relative;
            color: white;
        }
        
        .all-news-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            position: relative;
            z-index: 2;
        }
        
        .all-news-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            padding-bottom: 50px;
        }
        
        .news-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 20px;
        }
        
        .news-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        
        .news-content {
            padding: 10px 0;
        }
        
        .news-date {
            font-size: 0.9rem;
            color: #00ccff;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .news-headline {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: #333;
            line-height: 1.4;
        }
        
        .news-excerpt {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .news-read-more {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(45deg, #00ffcc, #00ccff);
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            font-weight: 600;
            transition: transform 0.3s ease;
        }
        
        .news-read-more:hover {
            transform: translateX(5px);
        }
        
        .back-button {
            display: inline-block;
            margin-bottom: 30px;
            padding: 10px 20px;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
            font-weight: 600;
        }
        
        .back-button:hover {
            background: rgba(0, 0, 0, 0.8);
        }
        
        /* Empty state styling */
        .no-news {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            color: #333;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .news-grid {
                grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
                gap: 20px;
            }
            
            .all-news-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="all-news-container">
        <a href="index.php" class="back-button">← Back to Home</a>
        <h1 class="all-news-title">All Department News</h1>
        
        <div class="news-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $formatted_date = date('d M Y', strtotime($row['date']));
                    $excerpt = substr($row['content'], 0, 150) . "...";
                    
                    echo "
                    <div class='news-item'>
                        <div class='news-content'>
                            <div class='news-date'>" . strtoupper($formatted_date) . "</div>
                            <h3 class='news-headline'>" . htmlspecialchars($row['headline']) . "</h3>
                            <p class='news-excerpt'>" . htmlspecialchars($excerpt) . "</p>
                            <a href='news-detail.php?id=" . $row['id'] . "' class='news-read-more'>Read More</a>
                        </div>
                    </div>";
                }
            } else {
                echo "<div class='no-news'><p>No news available at the moment.</p></div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
