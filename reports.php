<?php
$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch reports from database
$sql = "SELECT * FROM reports ORDER BY date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Department Name</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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

        /* Dropdown menu */
        .dropdown {
            position: relative;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: rgba(4,9,30,0.9);
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            left: 0;
            top: 100%;
            border-radius: 4px;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }
        .dropdown:hover .dropdown-menu {
            display: block;
            opacity: 1;
            transform: translateY(0);
            animation: fadeIn 0.3s ease-in-out;
        }
        .dropdown-menu li {
            display: block !important;
            text-align: left;
        }
        .dropdown-menu li a {
            padding: 10px 15px;
            display: block;
        }
        
        /* Sign In button */
        .button-86 {
            display: inline-block;
            padding: 6px 15px;
            text-decoration: none;
            border-radius: 30px;
            color: white;
            background: linear-gradient(to right, #f44336, #ff7675);
            box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4);
            transition: all 0.3s ease;
            border: none;
            outline: none;
            font-weight: 500;
        }
        .button-86:hover {
            background: linear-gradient(to right, #ff7675, #f44336);
            transform: translateY(-2px);
            box-shadow: 0 7px 15px rgba(244, 67, 54, 0.5);
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
            padding-bottom: 50px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 3px;
            background: linear-gradient(to right, #f44336, #ff7675);
        }
        
        /* Enhanced styles for reports table */
        .reports-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .reports-table th, .reports-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .reports-table th {
            background: linear-gradient(to right, #f44336, #ff7675);
            color: white;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        
        .reports-table tr {
            transition: all 0.3s ease;
        }
        
        .reports-table tr:hover {
            background-color: #f9f9f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .reports-table tbody tr {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }
        
        .reports-table tbody tr:nth-child(1) { animation-delay: 0.1s; }
        .reports-table tbody tr:nth-child(2) { animation-delay: 0.2s; }
        .reports-table tbody tr:nth-child(3) { animation-delay: 0.3s; }
        .reports-table tbody tr:nth-child(4) { animation-delay: 0.4s; }
        .reports-table tbody tr:nth-child(5) { animation-delay: 0.5s; }
        .reports-table tbody tr:nth-child(6) { animation-delay: 0.6s; }
        .reports-table tbody tr:nth-child(7) { animation-delay: 0.7s; }
        .reports-table tbody tr:nth-child(8) { animation-delay: 0.8s; }
        .reports-table tbody tr:nth-child(9) { animation-delay: 0.9s; }
        .reports-table tbody tr:nth-child(10) { animation-delay: 1.0s; }
        
        .view-btn {
            display: inline-block;
            background: linear-gradient(to right, #4CAF50, #45a049);
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(76, 175, 80, 0.3);
            font-weight: 500;
            cursor: pointer;
        }
        
        .view-btn:hover {
            background: linear-gradient(to right, #45a049, #4CAF50);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(76, 175, 80, 0.4);
        }
        
        .category-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            box-shadow: 0 2px 4px rgba(52, 152, 219, 0.3);
            transition: all 0.3s ease;
        }
        
        .category-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.4);
        }
        
        /* Modal styles for document viewer */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            animation: fadeIn 0.3s ease-out;
        }
        
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: 5% auto;
            padding: 25px;
            width: 90%;
            height: 85%;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 8px;
            animation: scaleUp 0.4s ease-out;
            transform-origin: center;
        }
        
        @keyframes scaleUp {
            0% { transform: scale(0.7); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .close {
            position: absolute;
            right: 25px;
            top: 15px;
            color: #f44336;
            font-size: 35px;
            font-weight: bold;
            cursor: pointer;
            z-index: 1010;
            transition: all 0.3s ease;
        }
        
        .close:hover {
            color: #e74c3c;
            transform: rotate(90deg);
        }
        
        #modal-title {
            margin-bottom: 20px;
            color: #333;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }
        
        #modal-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, #f44336, #ff7675);
        }
        
        .iframe-container {
            width: 100%;
            height: calc(100% - 50px);
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .iframe-container iframe {
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 8px;
        }
        
        .loading-indicator {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        
        .loading-spinner {
            width: 70px;
            height: 70px;
            position: relative;
            margin: 0 auto 20px;
        }
        
        .loading-spinner div {
            box-sizing: border-box;
            display: block;
            position: absolute;
            width: 64px;
            height: 64px;
            margin: 8px;
            border: 6px solid #3498db;
            border-radius: 50%;
            animation: loading-spinner 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
            border-color: #3498db transparent transparent transparent;
        }
        
        .loading-spinner div:nth-child(1) {
            animation-delay: -0.45s;
        }
        
        .loading-spinner div:nth-child(2) {
            animation-delay: -0.3s;
        }
        
        .loading-spinner div:nth-child(3) {
            animation-delay: -0.15s;
        }
        
        @keyframes loading-spinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading-text {
            color: #333;
            font-size: 16px;
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 10px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 0.6; }
            50% { opacity: 1; }
            100% { opacity: 0.6; }
        }
        
        /* Filter styles */
        .filter-section {
            margin-bottom: 30px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            animation: fadeIn 0.8s ease-out;
        }
        
        .filter-section label {
            font-weight: 500;
            color: #333;
        }
        
        .filter-section select, .filter-section input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 30px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .filter-section select:focus, .filter-section input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 2px 10px rgba(52, 152, 219, 0.2);
        }
        
        .filter-btn {
            background: linear-gradient(to right, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(52, 152, 219, 0.3);
            font-weight: 500;
        }
        
        .filter-btn:hover {
            background: linear-gradient(to right, #2980b9, #3498db);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(52, 152, 219, 0.4);
        }
        
        .reset-btn {
            background: linear-gradient(to right, #95a5a6, #7f8c8d);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(127, 140, 141, 0.3);
            font-weight: 500;
        }
        
        .reset-btn:hover {
            background: linear-gradient(to right, #7f8c8d, #95a5a6);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(127, 140, 141, 0.4);
        }
        
        /* Download button */
        .download-btn {
            display: inline-block;
            background: linear-gradient(to right, #e67e22, #d35400);
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 30px;
            margin-left: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(230, 126, 34, 0.3);
            font-weight: 500;
        }
        
        .download-btn:hover {
            background: linear-gradient(to right, #d35400, #e67e22);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(230, 126, 34, 0.4);
        }
        
        /* Animation keyframes */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInRight {
            from { 
                opacity: 0;
                transform: translateX(-20px);
            }
            to { 
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* No reports message */
        .no-reports {
            text-align: center;
            margin-top: 30px;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            animation: fadeIn 0.8s ease-out;
        }
        
        .no-reports i {
            font-size: 50px;
            color: #95a5a6;
            margin-bottom: 15px;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .navbar {
                justify-content: center;
            }
            .poster {
                font-size: 40px;
                height: 300px;
            }
            .dropdown-menu {
                position: static;
                background-color: transparent;
                box-shadow: none;
                display: none;
                width: 100%;
                padding-left: 15px;
            }
            .dropdown:hover .dropdown-menu {
                display: block;
            }
            .dropdown-menu li {
                padding: 5px 0;
            }
            .dropdown-menu li a {
                padding: 5px 0;
            }
            .reports-table thead {
                display: none;
            }
            .reports-table, .reports-table tbody, .reports-table tr, .reports-table td {
                display: block;
                width: 100%;
            }
            .reports-table tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 8px;
                overflow: hidden;
            }
            .reports-table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }
            .reports-table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: bold;
                text-align: left;
            }
            .filter-section {
                flex-direction: column;
                align-items: flex-start;
            }
            .filter-section > div {
                width: 100%;
            }
            .modal-content {
                width: 95%;
                height: 80%;
                margin: 10% auto;
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
                  <li><a href="student/studentcentric.html">STUDENT CENTRIC ACTIVITIES</a></li>
                  <li><a href="facultycentric.php">FACULTY CENTRIC ACTIVITIES</a></li>
                  <li><a href="reports.php">DEPARTMENT REPORTS ARCHIVE</a></li>
              </ul>
          </li>
          <li><a href="contact.php">CONTACT</a></li>
          <li><a href="gallery.html">GALLERY</a></li>
          <li><a href="Admin/signin.php" class="login-btn">SIGN IN</a></li>
        </ul>
    </div>
    <i class="fa fa-bars" onclick="showMenu()"></i>
</nav>

        <div class="poster">Reports</div>
    </section>

    <div class="container">
        <h2 class="animate__animated animate__fadeIn">Department Reports Archive</h2>
        
        <!-- Filter section - Now center aligned -->
        <div class="filter-section">
            <div>
                <label for="category-filter">Category:</label>
                <select id="category-filter" name="category">
                    <option value="">All Categories</option>
                    <?php
                    // Get unique categories
                    $categories_query = "SELECT DISTINCT category FROM reports WHERE category IS NOT NULL AND category != ''";
                    $categories_result = $conn->query($categories_query);
                    if($categories_result) {
                        while($category_row = $categories_result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($category_row['category']) . "'>" . htmlspecialchars($category_row['category']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div>
                <label for="year-filter">Year:</label>
                <select id="year-filter" name="year">
                    <option value="">All Years</option>
                    <?php
                    // Get unique years
                    $years_query = "SELECT DISTINCT YEAR(date) as year FROM reports ORDER BY year DESC";
                    $years_result = $conn->query($years_query);
                    if($years_result) {
                        while($year_row = $years_result->fetch_assoc()) {
                            echo "<option value='" . $year_row['year'] . "'>" . $year_row['year'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div>
                <label for="search-input">Search:</label>
                <input type="text" id="search-input" placeholder="Search by title or name">
            </div>
            
            <button class="filter-btn" onclick="filterReports()">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
            <button class="reset-btn" onclick="resetFilters()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
        
        <!-- Reports table - Updated column titles -->
        <?php if($result && $result->num_rows > 0): ?>
            <table class="reports-table" id="reports-table">
                <thead>
                    <tr>
                        <th>Report Name</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr data-category="<?= htmlspecialchars($row['category']) ?>" data-year="<?= date('Y', strtotime($row['date'])) ?>">
                            <td data-label="Report Name"><?= htmlspecialchars($row['title']) ?></td>
                            <td data-label="Title"><?= htmlspecialchars($row['name']) ?></td>
                            <td data-label="Category">
                                <?php if(!empty($row['category'])): ?>
                                    <span class="category-badge"><?= htmlspecialchars($row['category']) ?></span>
                                <?php else: ?>
                                    <span class="category-badge" style="background: linear-gradient(to right, #95a5a6, #7f8c8d);">General</span>
                                <?php endif; ?>
                            </td>
                            <td data-label="Date"><?= date('F d, Y', strtotime($row['date'])) ?></td>
                            <td data-label="Actions">
                                <a href="#" class="view-btn" data-link="<?= htmlspecialchars($row['drive_link']) ?>" data-title="<?= htmlspecialchars($row['title']) ?>">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="<?= htmlspecialchars($row['drive_link']) ?>" class="download-btn" download target="_blank">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-reports">
                <i class="fas fa-file-alt"></i>
                <h3>No Reports Found</h3>
                <p>There are currently no reports available. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal for document viewer -->
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modal-title"></h3>
            <div class="iframe-container" id="iframe-container">
                <div class="loading-indicator" id="loading-indicator">
                    <div class="loading-spinner">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                    <p class="loading-text">Loading document... Please wait</p>
                </div>
                <!-- iframe will be added here dynamically -->
            </div>
        </div>
    </div>

    <script>
       // Toggle menu for mobile
function showMenu() {
    var navLinks = document.getElementById("navLinks");
    navLinks.style.right = "0";
}

function hideMenu() {
    var navLinks = document.getElementById("navLinks");
    navLinks.style.right = "-200px";
}

// Initialize the page with animations
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all view buttons
    var viewButtons = document.querySelectorAll('.view-btn');
    viewButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var driveLink = this.getAttribute('data-link');
            var title = this.getAttribute('data-title');
            viewReport(driveLink, title);
        });
    });
    
    // Add event listeners to all download buttons
    var downloadButtons = document.querySelectorAll('.download-btn');
    downloadButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var driveLink = this.getAttribute('href');
            downloadReport(driveLink);
        });
    });
    
    // Animation for table rows on scroll
    animateOnScroll();
});

// Animate elements when they come into view
function animateOnScroll() {
    var elements = document.querySelectorAll('.reports-table tbody tr');
    
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    elements.forEach(function(element) {
        observer.observe(element);
    });
}

// Function to convert Google Drive link to embedded viewer link
function convertToPdfViewerLink(driveLink) {
    // Extract file ID from Google Drive link
    var fileId = '';
    
    // Pattern for Google Drive links
    if (driveLink.includes('drive.google.com')) {
        // Extract the file ID based on common Google Drive URL patterns
        if (driveLink.includes('/file/d/')) {
            fileId = driveLink.split('/file/d/')[1].split('/')[0];
        } else if (driveLink.includes('id=')) {
            fileId = driveLink.split('id=')[1].split('&')[0];
        }
        
        // Return Google Drive viewer URL
        return 'https://drive.google.com/file/d/' + fileId + '/preview';
    }
    
    // If not a Google Drive link, return original
    return driveLink;
}

// Convert Google Drive link to direct download link
function convertToDownloadLink(driveLink) {
    var fileId = '';
    
    if (driveLink.includes('drive.google.com')) {
        if (driveLink.includes('/file/d/')) {
            fileId = driveLink.split('/file/d/')[1].split('/')[0];
        } else if (driveLink.includes('id=')) {
            fileId = driveLink.split('id=')[1].split('&')[0];
        }
        
        // This is a more reliable Google Drive download URL
        return 'https://drive.google.com/uc?id=' + fileId + '&export=download';
    }
    
    return driveLink;
}

// Function to handle report download
function downloadReport(driveLink) {
    // Convert to proper download link
    var downloadLink = convertToDownloadLink(driveLink);
    
    // Create a temporary anchor element
    var tempLink = document.createElement('a');
    tempLink.href = downloadLink;
    tempLink.setAttribute('download', ''); // This sets the download attribute
    tempLink.setAttribute('target', '_blank');
    
    // Append to body, click, and remove
    document.body.appendChild(tempLink);
    tempLink.click();
    
    // Small delay before removing the element
    setTimeout(function() {
        document.body.removeChild(tempLink);
    }, 100);
}

// View report in modal with enhanced PDF viewing
function viewReport(driveLink, title) {
    var modal = document.getElementById("reportModal");
    var container = document.getElementById("iframe-container");
    var modalTitle = document.getElementById("modal-title");
    var loadingIndicator = document.getElementById("loading-indicator");
    
    // Set the title
    modalTitle.textContent = title;
    
    // Show loading indicator
    loadingIndicator.style.display = "block";
    
    // Format Google Drive link for PDF viewing
    var formattedLink = convertToPdfViewerLink(driveLink);
    
    // Create iframe
    var iframe = document.createElement('iframe');
    iframe.style.display = 'none'; // Hide initially until loaded
    iframe.src = formattedLink;
    iframe.setAttribute('allowfullscreen', true);
    iframe.setAttribute('sandbox', 'allow-scripts allow-same-origin allow-popups allow-forms');
    
    // Create iframe container if it doesn't exist
    if (!container.querySelector('iframe')) {
        // Clear previous content (except loading indicator)
        var oldIframe = container.querySelector('iframe');
        if (oldIframe) {
            container.removeChild(oldIframe);
        }
        
        container.appendChild(iframe);
    } else {
        // Replace existing iframe
        var oldIframe = container.querySelector('iframe');
        container.replaceChild(iframe, oldIframe);
    }
    
    // Show iframe when loaded
    iframe.onload = function() {
        loadingIndicator.style.display = "none";
        iframe.style.display = "block";
        iframe.classList.add('animate__animated', 'animate__fadeIn');
    };
    
    // Handle iframe loading errors
    iframe.onerror = function() {
        loadingIndicator.style.display = "none";
        container.innerHTML += '<div class="error-message"><i class="fas fa-exclamation-circle"></i><p>Error loading document. Please try downloading instead.</p></div>';
    };
    
    // Show the modal
    modal.style.display = "block";
    
    // Add event to close modal when clicking outside content
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    };
    
    // Prevent scrolling of body when modal is open
    document.body.style.overflow = "hidden";
}

// Close the modal
function closeModal() {
    var modal = document.getElementById("reportModal");
    modal.style.display = "none";
    
    // Re-enable scrolling when modal is closed
    document.body.style.overflow = "auto";
}

// Filter reports based on user selection
function filterReports() {
    var categoryFilter = document.getElementById("category-filter").value;
    var yearFilter = document.getElementById("year-filter").value;
    var searchText = document.getElementById("search-input").value.toLowerCase();
    
    var rows = document.querySelectorAll("#reports-table tbody tr");
    var foundMatch = false;
    
    rows.forEach(function(row) {
        var category = row.getAttribute("data-category");
        var year = row.getAttribute("data-year");
        var title = row.querySelector("td[data-label='Report Name']").textContent.toLowerCase();
        var name = row.querySelector("td[data-label='Title']").textContent.toLowerCase();
        
        var categoryMatch = !categoryFilter || category === categoryFilter;
        var yearMatch = !yearFilter || year === yearFilter;
        var searchMatch = !searchText || title.includes(searchText) || name.includes(searchText);
        
        if (categoryMatch && yearMatch && searchMatch) {
            row.style.display = "";
            foundMatch = true;
            
            // Add animation to matching rows
            row.classList.add('animate__animated', 'animate__fadeIn');
            setTimeout(function() {
                row.classList.remove('animate__animated', 'animate__fadeIn');
            }, 500);
        } else {
            row.style.display = "none";
        }
    });
    
    // Show or hide "No reports found" message
    var table = document.getElementById("reports-table");
    var noReports = document.querySelector(".no-reports");
    
    if (!foundMatch) {
        if (!noReports) {
            noReports = document.createElement("div");
            noReports.className = "no-reports animate__animated animate__fadeIn";
            noReports.innerHTML = `
                <i class="fas fa-search"></i>
                <h3>No Reports Match Your Criteria</h3>
                <p>Try adjusting your filters or search terms.</p>
            `;
            table.parentNode.insertBefore(noReports, table.nextSibling);
            table.style.display = "none";
        } else {
            noReports.style.display = "block";
            table.style.display = "none";
        }
    } else {
        if (noReports) {
            noReports.style.display = "none";
        }
        table.style.display = "table";
    }
}

// Reset all filters
function resetFilters() {
    document.getElementById("category-filter").value = "";
    document.getElementById("year-filter").value = "";
    document.getElementById("search-input").value = "";
    
    var rows = document.querySelectorAll("#reports-table tbody tr");
    rows.forEach(function(row) {
        row.style.display = "";
        
        // Add reset animation to all rows
        row.classList.add('animate__animated', 'animate__fadeIn');
        setTimeout(function() {
            row.classList.remove('animate__animated', 'animate__fadeIn');
        }, 500);
    });
    
    // Hide any "No reports found" message
    var noReports = document.querySelector(".no-reports");
    if (noReports) {
        noReports.style.display = "none";
    }
    
    // Show the table
    var table = document.getElementById("reports-table");
    if (table) {
        table.style.display = "table";
    }
}

// Enable keyboard navigation for modal
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeModal();
    }
});

// Progressive enhancement - lazy load animations
document.addEventListener('scroll', function() {
    var animationElements = document.querySelectorAll('.reports-table tbody tr');
    
    animationElements.forEach(function(element) {
        if (isElementInViewport(element) && element.style.opacity !== "1") {
            element.style.opacity = "1";
        }
    });
});

// Check if element is in viewport
function isElementInViewport(el) {
    var rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}
    </script>
</body>
</html>
