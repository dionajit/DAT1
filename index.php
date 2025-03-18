<?php
$host = 'localhost';
$db = 'department_portal';
$user = 'root';
$pass = '12345';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM news ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DAT</title>
    <link rel="icon" href="images/title.png" type="image/gif">
    <link rel="stylesheet" href="styles2.css" />
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


  <div class="text-box">
      <h1>Department of Statistics and Data Science</h1>
      <p>
          Providing a dynamic research environment and effective education, comprising state-of-the-art training in
          scientific methodologies for data-driven solutions.
      </p>
      <a href="" class="hero-btn">Learn More</a>
  </div>
</section>

    <!-- <! -----------------DEPARTMENT News---------------   --- >     -->
<section class="department-news">
  <div class="news-overlay"></div>
  <h1>Department News</h1>
  <div class="news-wrapper">
      <div class="scroller" data-speed="slow">
          <div class="scroller__inner">
              <?php 
              $query = "SELECT * FROM news ORDER BY date DESC LIMIT 5";
              $result = $conn->query($query);
              
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      $formatted_date = date('d M Y', strtotime($row['date']));
                      echo "
                      <div class='news-col'>
                          <div class='news-date'>" . strtoupper($formatted_date) . "</div>
                          <h3>" . htmlspecialchars($row['headline']) . "</h3>
                          <p>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>
                          <div class='news-footer'>
                              
                              <a href='news-detail.php?id=" . $row['id'] . "' class='badge'>Read More→</a>
                          </div>
                      </div>";
                  }
                  $result->data_seek(0);
              } else {
                  echo "<div class='news-col'><p>No news available at the moment.</p></div>";
              }
              ?>
              <?php 
              if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      $formatted_date = date('d M Y', strtotime($row['date']));
                      echo "
                      <div class='news-col'>
                          <div class='news-date'>" . strtoupper($formatted_date) . "</div>
                          <h3>" . htmlspecialchars($row['headline']) . "</h3>
                          <p>" . htmlspecialchars(substr($row['content'], 0, 150)) . "...</p>
                           <div class='news-footer'>
                              
                              <a href='news-detail.php?id=" . $row['id'] . "' class='badge'>Read More→</a>
                          </div>
                      </div>";
                  }
              }
              ?>
          </div>
      </div>
  </div>
  <a href="all-news.php" class="news-btn">View All News</a>
</section>

    <!-- <! -----------------COURSE---------------   --- >     -->
    <section class="course">
      <h1>Courses We Offer</h1>
      <p class="sub-text">Explore our programs designed for the next generation of data and statistical experts.</p>
  
      <div class="ag-format-container">
          <div class="ag-courses_box">
              <div class="ag-courses_item">
                  <a href="https://christuniversity.in/admission-ug" class="ag-courses-item_link">
                      <div class="ag-courses-item_bg"></div>
                      <div class="ag-courses-item_title">Undergraduate</div>
                      <div class="ag-courses-item_date-box">
                          Programs:
                          <span class="ag-courses-item_date">
                              B.Sc. (Computer Science, Statistics), B.Sc. (Data Science, Mathematics), B.Sc. (Data Science & AI)
                          </span>
                      </div>
                  </a>
              </div>
  
              <div class="ag-courses_item">
                  <a href="https://christuniversity.in/admission-pg" class="ag-courses-item_link">
                      <div class="ag-courses-item_bg"></div>
                      <div class="ag-courses-item_title">Postgraduate</div>
                      <div class="ag-courses-item_date-box">
                          Programs:
                          <span class="ag-courses-item_date">
                              M.Sc. Statistics, M.Sc. Data Analytics, M.Sc. Data Science
                          </span>
                      </div>
                  </a>
              </div>
  
              <div class="ag-courses_item">
                  <a href="https://christuniversity.in/admission-phd" class="ag-courses-item_link">
                      <div class="ag-courses-item_bg"></div>
                      <div class="ag-courses-item_title">Doctoral (PhD)</div>
                      <div class="ag-courses-item_date-box">
                          Programs:
                          <span class="ag-courses-item_date">
                              PhD in Statistics, PhD in Data Science
                          </span>
                      </div>
                  </a>
              </div>
          </div>
      </div>
  </section>
  
  <!-- Separator Header -->
<div class="section-separator">
  <h2>Our Mission & Vision</h2>
</div>

<!-- ---------- Mission & Vision Section ---------- -->
<section class="mission-vision-section">
  <div class="mission-container">
    <div class="card slide-in" id="vision-card">
      <h2>Vision</h2>
      <p>Excellence and Service</p>
    </div>
    <div class="card slide-in" id="mission-card">
      <h2>Mission</h2>
      <p>CHRIST (Deemed to be University) is a nurturing ground for an individual's holistic development to make effective contribution to society in a dynamic environment.</p>
    </div>
  </div>
</section>

<!-- Core Values Section -->
<section class="core-values-section">
  <div class="core-values-container">
    <h2>Core Values</h2>
    <div class="values-grid">
      <div class="value-card fade-in"><h3>Faith in God</h3></div>
      <div class="value-card fade-in"><h3>Moral Uprightness</h3></div>
      <div class="value-card fade-in"><h3>Love of Fellow Beings</h3></div>
      <div class="value-card fade-in"><h3>Social Responsibility</h3></div>
      <div class="value-card fade-in"><h3>Pursuit of Excellence</h3></div>
    </div>
  </div>
</section>

<!-- Testimonials Section with Customizable Theme -->
<section class="testimonials">
  <div class="testimonials-container">
    <h1>What Our Students Say</h1>
    
    <div class="testimonial-slider">
      <!-- Main testimonial display -->
      <div class="testimonial-main">
        <div class="testimonial-card">
          <div class="testimonial-quote">
            <svg class="quote-icon quote-left" viewBox="0 0 24 24" width="40" height="40">
              <path d="M10,7L8,11H11V17H5V11L7,7H10M18,7L16,11H19V17H13V11L15,7H18Z" fill="currentColor" opacity="0.3"/>
            </svg>
            <div class="testimonial-text" id="testimonial-text">
              The Department of Statistics and Data Science provided me with hands-on learning, expert faculty, and industry exposure. The well-structured curriculum and research opportunities helped me secure an internship in analytics.
            </div>
            <svg class="quote-icon quote-right" viewBox="0 0 24 24" width="40" height="40">
              <path d="M14,17L16,13H13V7H19V13L17,17H14M6,17L8,13H5V7H11V13L9,17H6Z" fill="currentColor" opacity="0.3"/>
            </svg>
          </div>
          <div class="testimonial-author">
            <div class="avatar">
              <div class="avatar-circle" id="avatar-bg">D</div>
            </div>
            <div class="author-info">
              <h3 id="testimonial-name">Dion</h3>
              <p id="testimonial-program">MSc Data Science, 2024</p>
              <div class="rating" id="testimonial-rating">
                <svg class="star-icon" viewBox="0 0 24 24" width="18" height="18"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor"/></svg>
                <svg class="star-icon" viewBox="0 0 24 24" width="18" height="18"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor"/></svg>
                <svg class="star-icon" viewBox="0 0 24 24" width="18" height="18"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor"/></svg>
                <svg class="star-icon" viewBox="0 0 24 24" width="18" height="18"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor"/></svg>
                <svg class="star-icon" viewBox="0 0 24 24" width="18" height="18"><path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor" opacity="0.5"/></svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Progress bar -->
      <div class="testimonial-progress-container">
        <div class="testimonial-progress" id="testimonial-progress"></div>
      </div>

      <!-- Navigation dots -->
      <div class="testimonial-nav">
        <span class="nav-dot active" data-index="0"></span>
        <span class="nav-dot" data-index="1"></span>
        <span class="nav-dot" data-index="2"></span>
        <span class="nav-dot" data-index="3"></span>
        <span class="nav-dot" data-index="4"></span>
      </div>
      
      <!-- Navigation arrows -->
      <div class="testimonial-arrows">
        <button class="nav-arrow prev-arrow">
          <svg viewBox="0 0 24 24" width="24" height="24">
            <path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" fill="currentColor"/>
          </svg>
        </button>
        <button class="nav-arrow next-arrow">
          <svg viewBox="0 0 24 24" width="24" height="24">
            <path d="M8.59,16.58L13.17,12L8.59,7.41L10,6L16,12L10,18L8.59,16.58Z" fill="currentColor"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</section>

    <!-- ----------CALL TO ACTION----------  -->

    <section class="cta">
      <h1>
        Enroll For Our Various Courses <br />
      </h1>
      <a href="contact.html" class="hero-btn">CONTACT US</a>
    </section>

    <!-- -----------FOOTER----------  -->
    <section class="footer">

      <div class="icons">
        <i class="fa fa-facebook"></i>
        <i class="fa fa-instagram"></i>
        <i class="fa fa-twitter"></i>
        <i class="fa fa-linkedin"></i>
      </div>
      <p>Made by Jeremy Monica & Dion </p>
    </section>

    <!-- <! ---------JavaScript for Toggle Menu ---------  > -->
    <script>
      // Navigation menu functions
var navLinks = document.getElementById("navLinks");

function showMenu() {
  navLinks.style.right = "0";
}
function hideMenu() {
  navLinks.style.right = "-200px";
}

// Wait for DOM to be fully loaded before running scripts
document.addEventListener("DOMContentLoaded", function () {
  // Mission & Vision card hover effects
  document.getElementById('vision-card').addEventListener('mouseenter', () => {
    document.getElementById('mission-card').style.opacity = '0.5';
  });

  document.getElementById('vision-card').addEventListener('mouseleave', () => {
    document.getElementById('mission-card').style.opacity = '1';
  });

  document.getElementById('mission-card').addEventListener('mouseenter', () => {
    document.getElementById('vision-card').style.opacity = '0.5';
  });

  document.getElementById('mission-card').addEventListener('mouseleave', () => {
    document.getElementById('vision-card').style.opacity = '1';
  });

  // Animation on scroll functionality
  const slideInElements = document.querySelectorAll(".slide-in");
  const fadeInElements = document.querySelectorAll(".fade-in");

  function revealOnScroll() {
    const triggerBottom = window.innerHeight * 0.85;

    slideInElements.forEach((el) => {
      const boxTop = el.getBoundingClientRect().top;
      if (boxTop < triggerBottom) {
        el.classList.add("show");
      }
    });

    fadeInElements.forEach((el) => {
      const boxTop = el.getBoundingClientRect().top;
      if (boxTop < triggerBottom) {
        el.classList.add("show");
      }
    });
  }

  window.addEventListener("scroll", revealOnScroll);
  // Trigger once on load to show elements that are already in view
  revealOnScroll();

  // Initialize the infinite scroll for news section
  const scrollers = document.querySelectorAll(".scroller");

  // If a user hasn't opted in for reduced motion, then we add the animation
  if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
    addAnimation();
  }

  function addAnimation() {
    scrollers.forEach((scroller) => {
      // Add data-animated="true" to every .scroller on the page
      scroller.setAttribute("data-animated", true);

      // Make an array from the elements within .scroller__inner
      const scrollerInner = scroller.querySelector(".scroller__inner");
      const scrollerContent = Array.from(scrollerInner.children);

      // For each item in the array, clone it and add it to the .scroller__inner
      scrollerContent.forEach((item) => {
        const duplicatedItem = item.cloneNode(true);
        duplicatedItem.setAttribute("aria-hidden", true);
        scrollerInner.appendChild(duplicatedItem);
      });
    });
  }

  // Testimonial functionality
  // Testimonial data with custom brand colors for each person
  const testimonials = [
    {
      text: "The Department of Statistics and Data Science provided me with hands-on learning, expert faculty, and industry exposure. The well-structured curriculum and research opportunities helped me secure an internship in analytics.",
      name: "Dion",
      program: "MSc Data Science, 2024",
      rating: 4.5,
      color: "var(--primary-color)" // Use primary color from CSS variables
    },
    {
      text: "Studying here was an amazing experience! The faculty's expertise, real-world projects, and strong analytical foundation prepared me perfectly for the industry. I especially loved the collaborative environment.",
      name: "Monica",
      program: "BSc Data Science, 2023",
      rating: 4,
      color: "var(--accent-color)" // Use accent color from CSS variables
    },
    {
      text: "The program's emphasis on practical applications and statistical computing was invaluable. I gained skills that immediately translated to my role as a Data Analyst, and the department's industry connections helped me land my dream job.",
      name: "Jeremy",
      program: "MSc Statistics, 2024",
      rating: 5,
      color: "var(--primary-color-darker)" // Use darker primary
    },
    {
      text: "The faculty's dedication to student success makes this department special. They provided personalized mentoring and encouraged me to pursue research that aligned with my interests in machine learning and AI.",
      name: "Priya",
      program: "PhD Data Science, 2023",
      rating: 5,
      color: "var(--primary-color-lighter)" // Use lighter primary
    },
    {
      text: "I appreciated the balance between theoretical foundations and hands-on projects. The department's state-of-the-art computing facilities and strong industry partnerships gave me a competitive edge in the job market.",
      name: "Rahul",
      program: "BSc Statistics, 2024",
      rating: 4.5,
      color: "var(--accent-color)" // Use accent color again
    }
  ];

  let currentIndex = 0;
  const textElement = document.getElementById('testimonial-text');
  const nameElement = document.getElementById('testimonial-name');
  const programElement = document.getElementById('testimonial-program');
  const ratingElement = document.getElementById('testimonial-rating');
  const avatarBg = document.getElementById('avatar-bg');
  const progressBar = document.getElementById('testimonial-progress');
  const dots = document.querySelectorAll('.nav-dot');
  const prevBtn = document.querySelector('.prev-arrow');
  const nextBtn = document.querySelector('.next-arrow');
  const card = document.querySelector('.testimonial-card');
  
  // Variables for auto-rotation
  let interval;
  const rotationDuration = 6000; // 6 seconds
  let progressAnimation;
  
  // Setup progress bar animation
  function startProgressBar() {
    // Reset
    progressBar.style.width = '0%';
    
    // Create animation
    let startTime;
    const animate = (timestamp) => {
      if (!startTime) startTime = timestamp;
      const progress = (timestamp - startTime) / rotationDuration * 100;
      progressBar.style.width = `${Math.min(progress, 100)}%`;
      
      if (progress < 100) {
        progressAnimation = requestAnimationFrame(animate);
      }
    };
    
    progressAnimation = requestAnimationFrame(animate);
    
    // Return the animation ID so we can cancel it if needed
    return progressAnimation;
  }
  
  // Function to update testimonial display
  function updateTestimonial(index) {
    // Add fade-out class
    card.classList.add('fade-out');
    
    // After animation completes, update content and fade in
    setTimeout(() => {
      const testimonial = testimonials[index];
      textElement.textContent = testimonial.text;
      nameElement.textContent = testimonial.name;
      programElement.textContent = testimonial.program;
      avatarBg.textContent = testimonial.name.charAt(0);
      avatarBg.style.backgroundColor = testimonial.color;
      
      // Update rating stars
      ratingElement.innerHTML = '';
      const fullStars = Math.floor(testimonial.rating);
      const hasHalfStar = testimonial.rating % 1 !== 0;
      
      for (let i = 0; i < fullStars; i++) {
        const star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        star.setAttribute("class", "star-icon");
        star.setAttribute("viewBox", "0 0 24 24");
        star.setAttribute("width", "18");
        star.setAttribute("height", "18");
        star.innerHTML = '<path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor"/>';
        ratingElement.appendChild(star);
      }
      
      if (hasHalfStar) {
        const star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        star.setAttribute("class", "star-icon");
        star.setAttribute("viewBox", "0 0 24 24");
        star.setAttribute("width", "18");
        star.setAttribute("height", "18");
        star.innerHTML = '<path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor" opacity="0.5"/>';
        ratingElement.appendChild(star);
      }
      
      const emptyStars = 5 - Math.ceil(testimonial.rating);
      for (let i = 0; i < emptyStars; i++) {
        const star = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        star.setAttribute("class", "star-icon");
        star.setAttribute("viewBox", "0 0 24 24");
        star.setAttribute("width", "18");
        star.setAttribute("height", "18");
        star.innerHTML = '<path d="M12,17.27L18.18,21L16.54,13.97L22,9.24L14.81,8.62L12,2L9.19,8.62L2,9.24L7.45,13.97L5.82,21L12,17.27Z" fill="currentColor" opacity="0.2"/>';
        ratingElement.appendChild(star);
      }
      
      // Update active dot
      dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
      });
      
      // Remove fade-out class to fade in
      card.classList.add('fade-in');
      setTimeout(() => {
        card.classList.remove('fade-out');
        card.classList.remove('fade-in');
      }, 50);
    }, 300); // Match this timing with your CSS transition
  }

  // Function to go to the next testimonial
  function nextTestimonial() {
    currentIndex = (currentIndex + 1) % testimonials.length;
    updateTestimonial(currentIndex);
    if (progressAnimation) {
      cancelAnimationFrame(progressAnimation);
    }
    progressAnimation = startProgressBar();
  }

  // Function to go to the previous testimonial
  function prevTestimonial() {
    currentIndex = (currentIndex - 1 + testimonials.length) % testimonials.length;
    updateTestimonial(currentIndex);
    if (progressAnimation) {
      cancelAnimationFrame(progressAnimation);
    }
    progressAnimation = startProgressBar();
  }

  // Event listeners for dots
  dots.forEach((dot) => {
    dot.addEventListener('click', function() {
      currentIndex = parseInt(this.getAttribute('data-index'));
      updateTestimonial(currentIndex);
      if (progressAnimation) {
        cancelAnimationFrame(progressAnimation);
      }
      progressAnimation = startProgressBar();
    });
  });

  // Event listeners for arrows
  prevBtn.addEventListener('click', prevTestimonial);
  nextBtn.addEventListener('click', nextTestimonial);

  // Initialize progress bar
  progressAnimation = startProgressBar();

  // Auto-rotate testimonials
  interval = setInterval(nextTestimonial, rotationDuration);

  // Pause auto-rotation when user interacts with controls
  const controls = document.querySelectorAll('.nav-dot, .prev-arrow, .next-arrow');
  controls.forEach(control => {
    control.addEventListener('click', () => {
      clearInterval(interval);
      // Restart after a delay
      setTimeout(() => {
        interval = setInterval(nextTestimonial, rotationDuration);
      }, 10000);
    });
  });

  // Pause rotation when user hovers over testimonial
  card.addEventListener('mouseenter', () => {
    clearInterval(interval);
    if (progressAnimation) {
      cancelAnimationFrame(progressAnimation);
    }
  }); 

  card.addEventListener('mouseleave', () => {
    if (progressAnimation) {
      cancelAnimationFrame(progressAnimation);
    }
    progressAnimation = startProgressBar();
    interval = setInterval(nextTestimonial, rotationDuration);
  });

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      prevTestimonial();
    } else if (e.key === 'ArrowRight') {
      nextTestimonial();
    }
  });
});</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  </body>
</html>