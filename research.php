<?php
$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch articles from the database
$query = "SELECT id, title, author, date, type, abstract, link FROM research_papers";
$stmt = $pdo->query($query);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Research</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        .header{
    min-height: 30vh;
    width: 100%;
    background-image: linear-gradient(rgba(4,9,30,0.7),rgba(4,9,30,0.7)),url(images2/university.jpg);
    background-position: center;
    background-size: cover;
    margin-bottom: 50px;
    position: relative;
    
}
        nav {
            display: flex;
            padding: 2% 6%;
            justify-content: space-between;
            align-items: center;
        }

        nav img {
            width: 250px;
        }

        .nav-links {
            flex: 1;
            text-align: right;
        }

        .nav-links ul {
            padding: 0;
            margin: 0;
        }

        .nav-links ul li {
            list-style: none;
            display: inline-block;
            padding: 8px 12px;
            position: relative;
        }

        .nav-links ul li a {
            color: white;
            text-decoration: none;
            font-size: 15px;
        }

        .nav-links ul li::after {
            content: '';
            width: 0%;
            height: 2px;
            background: #f44336;
            display: block;
            margin: auto;
            transition: 0.5s;
        }

        .nav-links ul li:hover::after {
            width: 100%;
        }
        .dropdown {
    position: relative;
}

.dropdown-menu {
    display: none; /* Hide dropdown initially */
    position: absolute;
    top: 100%; /* Position it below the parent */
    left: 0;
    background: #444;
    min-width: 200px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    padding: 0;
}

.dropdown-menu li {
    display: block;
    padding: 10px;
    text-align: left;
}

.dropdown-menu li a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 10px;
    font-size: 14px;
}

/* Show dropdown on hover */
.dropdown:hover .dropdown-menu {
    display: block;
}


.secondary-nav {
            background: linear-gradient(135deg, #2b5876, #4e4376);
            padding: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 100;
        }

        .secondary-nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .secondary-nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .secondary-nav-links li {
            margin: 0 5px;
            position: relative;
        }

        .secondary-nav-links li a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 1px;
            border-radius: 30px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .secondary-nav-links li a:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
            z-index: -1;
            border-radius: 30px;
        }

        .secondary-nav-links li a:hover:before {
            transform: scaleX(1);
            transform-origin: left;
        }

        .secondary-nav-links li a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .secondary-nav-links li a i {
            margin-right: 8px;
        }

        .secondary-nav-links li a.active {
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .secondary-nav-container {
                flex-direction: column;
            }
            
            .secondary-nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .secondary-nav-links li {
                margin: 5px;
            }
            
            .secondary-nav-links li a {
                padding: 8px 15px;
                font-size: 12px;
            }
        }


.login-btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
            color: white !important;
            font-size: 16px;
            font-weight: 600;
            text-transform: uppercase;
            text-decoration: none;
            border-radius: 25px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.3);
        }

        .login-btn:hover {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(255, 75, 43, 0.5);
        }

        nav .fa {
            display: none;
        }

        .poster {
            text-align: center;
            height: 30vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 62px;
            font-weight: bold;
        }

        .container {
            width: 90%;
            margin: auto;
            margin-top: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .navbar {
                justify-content: center;
            }

            .poster {
                font-size: 40px;
                height: 300px;
            }
        }
    </style>
</head>
<body>

<section class="header">
<nav>
    <a href="index.php"><img src="images2/logo3.png" alt="logo" style="width: 250px; height: auto;" /></a>
    <div class="nav-links" id="navLinks">
        <i class="fa fa-times" onclick="hideMenu()"></i>
        <ul>
          <li><a href="index.php">HOME</a></li>
          <li><a href="about.html">ABOUT</a></li>
          <li class="dropdown">
              <a href="#">ACTIVITIES</a>
              <ul class="dropdown-menu">
                  <li><a href="student_activities.html">Student Centric Activities</a></li>
                  <li><a href="facultycentric.php">Faculty Centric Activities</a></li>
                  <li><a href="reports.php">Department Reports Archive</a></li>

              </ul>
          </li>
          <li><a href="contact.php">CONTACT</a></li>
          <li><a href="gallery.html">GALLERY</a></li>
          <li><a href="Admin/signin.php" class="login-btn">Sign In</a></li>

        </ul>
    </div>
    <i class="fa fa-bars" onclick="showMenu()"></i>
</nav>
    <div class="poster">
        Publications
    </div>

   

    <div class="secondary-nav">
        <div class="secondary-nav-container">
            <ul class="secondary-nav-links">
                <li><a href="facultycentric.php"><i class="fa fa-users"></i> FACULTY</a></li>
                <li><a href="brochure.html"><i class="fa fa-book"></i> BROCHURE</a></li>
                <li><a href="research.php"><i class="fa fa-flask"></i> RESEARCH</a></li>
                <li><a href="achi.html"><i class="fa fa-trophy"></i> ACHIEVEMENTS</a></li>
            </ul>
        </div>
    </div>
    </section>

    <div class="container">
        <h2>Research Publications</h2>

        <table id="researchTable" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Abstract</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?= htmlspecialchars($article['id']) ?></td>
                        <td><?= htmlspecialchars($article['title']) ?></td>
                        <td><?= htmlspecialchars($article['author']) ?></td>
                        <td><?= htmlspecialchars($article['date']) ?></td>
                        <td><?= htmlspecialchars($article['type']) ?></td>
                        <td><?= htmlspecialchars($article['abstract']) ?></td>
                        <td><a href="<?= htmlspecialchars($article['link']) ?>" target="_blank">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#researchTable').DataTable();
        });
    </script>

</body>
</html>