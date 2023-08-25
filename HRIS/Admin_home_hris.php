<?php 
    session_start();
    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    $conn = new mysqli('localhost', 'root', '', 'sa4');
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $name = ""; // Initialize the variable
    $surname = $user['surname'];
    $firstLetter = substr($surname, 0, 1);
    $name = $user['name'] . " " . $firstLetter . ".";
    $_SESSION['name'] = $name;
    $_SESSION['control_number'] = $user['control_number'];
    $_SESSION['image'] = $user['image'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['password'] = $user['password'];
    $_SESSION['access_level'] = $user['access_level'];
    include 'navbar_hris.php';

    $sql = "SELECT * FROM employees WHERE status = 'active'";
    $result = mysqli_query($conn, $sql);
    $total_active = mysqli_num_rows($result);

    $sql = "SELECT * FROM employees WHERE status = 'resigned'";
    $result = mysqli_query($conn, $sql);
    $total_resigned = mysqli_num_rows($result);
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            display: relative; /* Use flex display for alignment */
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0; /* Remove default margin to avoid unexpected spacing */
            background-image: url("images/aths-background.jpg");
            background-size: cover; /* Ensure the background covers the entire body */
            background-repeat: no-repeat; /* Prevent background image from repeating */
            background-position: center; /* Center the background image */
        }

        #digital-clock {
            font-family: 'Arial', sans-serif;
            font-size: 48px;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 20px;
            right: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
            <h1>Dashboard</h1>
            <?php 
                echo "Total Number of Active Employees: " . $total_active . "<br>";
                echo "Total Number of Resigned Employees: " . $total_resigned . "<br>";
            ?>
        </div>    
    </div>
    <div id="digital-clock"></div>
</body>
<script>
    // Function to update the clock every second
    function updateClock() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const seconds = now.getSeconds();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        
        // Convert to 12-hour format
        hours = hours % 12 || 12;
        
        // Add leading zeros
        hours = hours.toString().padStart(2, '0');
        minutes = minutes.toString().padStart(2, '0');
        
        const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
        document.getElementById('digital-clock').textContent = timeString;
    }

    // Update the clock immediately and set an interval to update every second
    updateClock();
    setInterval(updateClock, 1000);
</script>
</html>