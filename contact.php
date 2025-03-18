<?php
session_start();

// Database connection
$host = 'localhost'; // Change if needed
$db = 'department_portal';
$user = 'root'; // Your database username
$pass = '12345'; // Your database password

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();
    $stmt->close();

    // Set a session variable to indicate success
    $_SESSION['success'] = "Your message has been successfully sent!";
    // Redirect to the same page
    header("Location: contact.php");
    exit();
}


$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : '';
if ($success_message) {
    unset($_SESSION['success']); 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>University Website</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;600;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
    />
</head>
<body>
    <!-- -------------HEADER------------   -->

    <section class="sub-header">
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
      <h1>Contact Us</h1>
    </section>

    <!-- -----------CONTACT US--------- -->

    <section class="location">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.5559604618243!2d77.60361387484075!3d12.936236187375915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae15b277a93807%3A0x88518f37b39dabd0!2sChrist%20University!5e0!3m2!1sen!2sin!4v1739945844237!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </section>
    
    <section class="contact-us">
        <div class="row">
            <div class="contact-col">
                <div>
                    <i class="fa fa-home"></i>
                    <span>
                        <h5>CHRIST(Deemed to be University)</h5>
                        <p> DHARMARAM COLLEGE, Hosur Main Road, Bhavani Nagar, Post, Bengaluru, Karnataka 560029, India</p>
                    </span>
                </div>
                <div>
                    <i class="fa fa-phone"></i>
                    <span>
                        <h5>+91 80 4012 9400</h5>
                        <p>Monday to Saturday, 10AM to 6PM</p>
                    </span>
                </div>
                <div>
                    <i class="fa fa-envelope-o"></i>
                    <span>
                        <h5>mail@christuniversity.in</h5>
                        <p>E-mail us your query</p>
                    </span>
                </div>
            </div>
            <div class="contact-col">
                <form action="contact.php" method="post">
                    <input type="text" name="name" placeholder="Enter your name" required>
                    <input type="email" name="email" placeholder="Enter your E-mail Address" required>
                    <textarea rows="8" name="message" placeholder="Message" required></textarea>
                    <button type="submit" class="hero-btn red-btn">Send Message</button>
                </form>
                <?php if ($success_message): ?>
                    <script>
                        alert("<?php echo htmlspecialchars($success_message); ?>");
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- -----------FOOTER----------  -->
    <section class="footer">
        <div class="icons">
          <i class="fa fa-facebook"></i>
          <i class="fa fa-instagram"></i>
          <i class="fa fa-twitter"></i>
          <i class="fa fa-linkedin"></i>
        </div>
    </section>    

    <!-- <! ---------JavaScript for Toggle Menu ---------  > -->
    <script>
      var navLinks = document.getElementById("navLinks");

      function showMenu() {
        navLinks.style.right = "0";
      }
      function hideMenu() {
        navLinks.style.right = "-200px";
      }
    </script>
</body>
</html>