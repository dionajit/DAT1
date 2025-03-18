<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Centric</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }
        
        /* Header styles matching home page */
        .header {
            min-height: 30vh;
            width: 100%;
            background-image: linear-gradient(rgba(4,9,30,0.7),rgba(4,9,30,0.7)),url(images2/university.jpg);
            background-position: center;
            background-size: cover;
            position: relative;
        }
        
        /* Top navigation bar styles (matching home page) */
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

        /* Dropdown styles */
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

        .login-btn:visited {
            color: white !important;
            background: linear-gradient(90deg, #ff416c, #ff4b2b);
        }

        .login-btn:hover {
            background: linear-gradient(90deg, #ff4b2b, #ff416c);
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(255, 75, 43, 0.5);
        }

        nav .fa {
            display: none;
        }

        @media(max-width: 700px) {
            .nav-links ul li {
                display: block;
            }
            .nav-links {
                position: fixed;
                background: #f44336;
                height: 100vh;
                width: 200px;
                top: 0;
                right: -200px;
                text-align: left;
                z-index: 2;
                transition: 1s;
            }
            nav .fa {
                display: block;
                color: #fff;
                margin: 10px;
                font-size: 22px;
                cursor: pointer;
            }
            .nav-links ul {
                padding: 30px;
            }
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

        /* New Secondary Navigation Bar Styles */
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

        /* Testimonial/Faculty Cards Styles */
        .testimonial-slider {
            background: linear-gradient(to right, #252526, #0f1013);
            padding: 2em 2em 3em;
            color: rgb(14, 2, 2);
        }
        
        .testimonial-title h2 {
            padding-left: 3em;
            font-size: 3rem; 
            color: #f3eeee;
        }

        .card {
            margin: 0 0.5em;
            box-shadow: 2px 6px 8px 0 rgba(22, 22, 26, 0.18);
            border: none;
            height: 100%;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            background-color: #f3eeee;
            width: 3em;
            height: 3em;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }
        
        .card {
            background-color: rgb(239, 232, 232); 
            width: 500px; 
            height: 400px; 
            border-radius: 10px; 
            box-shadow: 2px 6px 8px rgba(0, 0, 0, 0.2); 
            margin: 0 auto; 
            padding: 15px; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
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
                  <li><a href="student/studentcentric.html">Student Centric Activities</a></li>
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
            Faculty Centric
        </div>
    </section>

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

    <!-- Faculty Testimonial Slider -->
    <div class="testimonial-slider">
        <div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="testimonial-title">
                            <i class="bi bi-quote display-1"></i>
                            <h2 class="fw-bold display-6">"Message from faculty"</h2>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                    <div class="col-md-8">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card">
                                    <div class="img-wrapper">
                                        <img src="img/4.png" class="d-block w-100" alt="..." style="max-width: 150px; height: auto;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">DR. Dalwin </h5>
                                        <p class="card-text">"Greetings! As a faculty member at Christ University, I am committed to fostering an environment of learning, curiosity, and academic excellence. I encourage students to explore new ideas, challenge perspectives, and strive for holistic growth. Looking forward to an enriching journey together!"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card">
                                    <div class="img-wrapper">
                                        <img src="img/7.png" class="d-block w-100" alt="..." style="max-width: 150px; height: auto;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">DR. Dibu AS</h5>
                                        <p class="card-text">"Greetings! As a faculty member at Christ University, I am committed to fostering an environment of learning, curiosity, and academic excellence. I encourage students to explore new ideas, challenge perspectives, and strive for holistic growth. Looking forward to an enriching journey together!"</p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card">
                                    <div class="img-wrapper">
                                        <img src="img/6.png" class="d-block w-100" alt="..." style="max-width: 150px; height: auto;">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">DR. Deepthi Das</h5>
                                        <p class="card-text">"Greetings! As a faculty member at Christ University, I am committed to fostering an environment of learning, curiosity, and academic excellence. I encourage students to explore new ideas, challenge perspectives, and strive for holistic growth. Looking forward to an enriching journey together!"</p>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End of carousel-inner -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        function showMenu() {
            document.getElementById("navLinks").style.right = "0";
        }
        
        function hideMenu() {
            document.getElementById("navLinks").style.right = "-200px";
        }
    
        document.addEventListener("DOMContentLoaded", function () {
            let carousel = document.querySelector("#carouselExampleControls");
            if (window.matchMedia("(min-width: 576px)").matches) {
                let items = document.querySelectorAll(".carousel-item");
                let cardWidth = items[0].getBoundingClientRect().width;
                let scrollPosition = 0;

                document.querySelector(".carousel-control-next").addEventListener("click", function () {
                    if (scrollPosition < (items.length - 1) * cardWidth) {
                        scrollPosition += cardWidth;
                        document.querySelector(".carousel-inner").scrollLeft = scrollPosition;
                    }
                });

                document.querySelector(".carousel-control-prev").addEventListener("click", function () {
                    if (scrollPosition > 0) {
                        scrollPosition -= cardWidth;
                        document.querySelector(".carousel-inner").scrollLeft = scrollPosition;
                    }
                });
            }

            // Set active tab in secondary nav
            function setActiveTab() {
                const currentPage = window.location.pathname.split("/").pop();
                const navLinks = document.querySelectorAll('.secondary-nav-links li a');
                
                navLinks.forEach(link => {
                    if (link.getAttribute('href') === currentPage) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });
            }
            
            setActiveTab();
        });
    </script>
</body>
</html>