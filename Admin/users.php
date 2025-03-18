<?php
session_start();
// Check if user is logged in
if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: signin.php");
    exit();
}

// Check if user is HOD
if ($_SESSION['role'] !== 'hod') {
    header("Location: dashboard.php");
    exit();
}

$servername = "localhost";
$db_username = "root";  // Default MySQL username
$db_password = "12345"; // Default MySQL password
$dbname = "department_portal";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        
        // Check if the user already exists
        $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $status = "error";
            $message = "User already exists with this username or email.";
        } else {
            $password = $_POST['password'];
            $role = $conn->real_escape_string($_POST['role']);
                        
            $sql = "INSERT INTO users (name, username, email, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $username, $email, $password, $role);
            
            if ($stmt->execute()) {
                $status = "success";
                $message = "User added successfully!";
            } else {
                $status = "error";
                $message = "Failed to add user: " . $conn->error;
            }
        }
        $check_stmt->close();
    }
    
    if (isset($_POST['delete_user'])) {
        $user_id = (int)$_POST['user_id']; 
        $sql = "DELETE FROM users WHERE id = ?"; 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        if($stmt->affected_rows > 0) {
            $status = "success";
            $message = "User deleted successfully!";
        } else {
            $status = "error";
            $message = "Failed to delete user.";
        }
        $stmt->close();
    }
}

// Fetch all users except the logged-in user
// First, get the current user's ID based on username
$current_user_sql = "SELECT id FROM users WHERE username = ?";
$current_user_stmt = $conn->prepare($current_user_sql);
$current_user_stmt->bind_param("s", $_SESSION['username']);
$current_user_stmt->execute();
$current_user_result = $current_user_stmt->get_result();
$current_user = $current_user_result->fetch_assoc();
$current_user_id = $current_user ? $current_user['id'] : 0;
$current_user_stmt->close();

// Fetch all users except the current user
$sql = "SELECT id, name, username, email, role FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Management</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #2980b9;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-color: #ecf0f1;
            --dark-color: #2d3436;
            --card-bg: #ffffff;
            --text-light: #F1F1F2;
            --text-dark: #2D2F36;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h2 {
            color: var(--dark-color);
            font-size: 1.8em;
        }

        /* Form Styles */
        .card {
            background: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            color: var(--primary-color);
            font-size: 1.4em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* User List Styles */
        .users-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .user-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.12);
        }

        .user-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--secondary-color);
        }

        .user-card.hod::before {
            background: var(--warning-color);
        }

        .user-info-section {
            flex-grow: 1;
            margin-bottom: 15px;
        }

        .user-info-section h4 {
            color: var(--dark-color);
            margin-bottom: 5px;
            font-size: 1.2em;
        }

        .user-meta {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
            color: #7f8c8d;
            font-size: 0.9em;
        }

        .role-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            margin-top: 10px;
        }

        .role-faculty {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--secondary-color);
        }

        .role-hod {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }

        .user-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-info">
            <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
            <p>Role: <?php echo ucfirst(htmlspecialchars($_SESSION['role'])); ?></p>
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
        
        <?php if ($_SESSION['role'] === 'hod'): ?>
        <div class="menu-item" onclick="location.href='users.php'" style="background: linear-gradient(to right, var(--secondary-color), var(--accent-color));">
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
            <h2><i class="fas fa-users"></i> User Management</h2>
        </div>
        
        <?php if (isset($status) && isset($message)): ?>
            <div class="alert alert-<?php echo $status; ?> fade-in">
                <i class="fas fa-<?php echo $status === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Add User Form -->
        <div class="card fade-in">
            <div class="card-header">
                <i class="fas fa-user-plus"></i> Add New User
            </div>
            <form method="POST" id="addUserForm">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="Enter full name">
                    </div>
                    
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required placeholder="Enter username">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email address">
                    </div>
                    
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control" required>
                        <option value="faculty">Faculty</option>
                        <option value="hod">HOD</option>
                    </select>
                </div>
                
                <button type="submit" name="add_user" class="btn btn-primary">
                    <i class="fas fa-plus-circle"></i> Add User
                </button>
            </form>
        </div>
        
        <!-- User List -->
        <div class="card fade-in">
            <div class="card-header">
                <i class="fas fa-user-friends"></i> Existing Users
            </div>
            <div class="users-container">
                <?php 
                $delay = 0.1;
                while($row = $result->fetch_assoc()): 
                $delay += 0.1;
                ?>
                <div class="user-card <?php echo $row['role']; ?>" style="animation-delay: <?php echo $delay; ?>s">
                    <div class="user-info-section">
                        <h4><?php echo htmlspecialchars($row['name']); ?></h4>
                        
                        <div class="user-meta">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($row['username']); ?>
                        </div>
                        
                        <div class="user-meta">
                            <i class="fas fa-envelope"></i>
                            <?php echo htmlspecialchars($row['email']); ?>
                        </div>
                        
                        <span class="role-badge role-<?php echo $row['role']; ?>">
                            <?php echo ucfirst($row['role']); ?>
                        </span>
                    </div>
                    
                    <div class="user-actions">
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete_user" class="btn btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                <i class="fas fa-trash-alt"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <script>
        // Basic form validation
        document.getElementById('addUserForm').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            
            if (password.length < 5) {
                e.preventDefault();
                alert('Password must be at least 5 characters long');
                return false;
            }
            
            const email = document.querySelector('input[name="email"]').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address');
                return false;
            }
            
            return true;
        });
        
        // Auto-hide alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        if (alerts.length > 0) {
            setTimeout(() => {
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => {
                        alert.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        }
    </script>
</body>
</html>