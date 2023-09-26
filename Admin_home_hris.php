<?php 
    session_start();
    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $name = ""; // Initialize the variable
    $surname = $user['surname'];
    $firstLetter = substr($surname, 0, 1);
    $name = $user['name'] . " " . $firstLetter . ".";
    $fname = $user['surname'] . ', ' . $user['name'] . ($user['middle_name'] != "" ? ' ' . $user['middle_name'] : '');
    $_SESSION['name'] = $name;
    $_SESSION['fname'] = $fname;
    $_SESSION['control_number'] = $user['control_number'];
    $_SESSION['image'] = $user['image'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['password'] = $user['password'];
    $_SESSION['access_level'] = $user['access_level'];

    if ($_SESSION['access_level'] == 'employee') {
        echo '<script>
                window.location="information.php?control=' . $_SESSION['control_number'] . '"
            </script>';
        exit;
    }

    include 'navbar_hris.php';
    change_default();
    
    $sql = "SELECT * FROM employees WHERE status = 'active'";
    $result = mysqli_query($conn, $sql);
    $total_active = mysqli_num_rows($result);

    $sql = "SELECT * FROM employees WHERE status = 'resigned'";
    $result = mysqli_query($conn, $sql);
    $total_resigned = mysqli_num_rows($result);

    $sql = "SELECT * FROM employees WHERE employment_status = 'permanent'";
    $result = mysqli_query($conn, $sql);
    $total_permanent = mysqli_num_rows($result);

    $sql = "SELECT * FROM employees WHERE employment_status = 'probationary'";
    $result = mysqli_query($conn, $sql);
    $total_probationary = mysqli_num_rows($result);
    
    $sql = "SELECT * FROM employees WHERE gender = 'male'";
    $result = mysqli_query($conn, $sql);
    $total_male = mysqli_num_rows($result);
    
    $sql = "SELECT * FROM employees WHERE gender = 'female'";
    $result = mysqli_query($conn, $sql);
    $total_female = mysqli_num_rows($result);

    function incrementYIS($conn, $timezone) {
        $sql_years = "SELECT * FROM employees WHERE status = 'active'";
        $stmt = $conn->prepare($sql_years);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $less_years_service = $row['less_yis'];

            $date_hired = $row['date_hired']; // Use the actual date_hired value from the database
            $resignationDateObj = new DateTime(); // Set resignation date to date_hired for active employees
            $resignationDateObj->setTimezone($timezone);

            //$dateHired = new DateTime($date_hired);
            $dateHired = new DateTime($date_hired, $timezone);
            $years_service = $dateHired->diff($resignationDateObj)->y;
    
            if ($less_years_service != 0) {
                $years_service = $years_service - $less_years_service;
            }

            // Update the years_in_service column in the database
            $updateSql = "UPDATE employees SET years_in_service = ? WHERE control_number = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("is", $years_service, $row['control_number']);
            $updateStmt->execute();
            $updateStmt->close();
        }
    
        $stmt->close();
    }

    function incrementAge($conn, $timezone) {
        $sql_years = "SELECT * FROM employees";
        $stmt = $conn->prepare($sql_years);
        $stmt->execute();
        $result = $stmt->get_result();
    
        while ($row = $result->fetch_assoc()) {
            $birthday = $row['birthday'];
            $today = new DateTime();
            $today->setTimezone($timezone);

            $my_birthday = new DateTime($birthday, $timezone);
            $age = $my_birthday->diff($today)->y;
    
            // Update the years_in_service column in the database
            $updateSql = "UPDATE employees SET age = ? WHERE control_number = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("is", $age, $row['control_number']);
            $updateStmt->execute();
            $updateStmt->close();
        }
    
        $stmt->close();
    }
    incrementAge($conn,$timezone);
    incrementYIS($conn,$timezone);
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>
body {
  display: relative;
  align-items: center;
  justify-content: center;
  height: 100vh;
  margin: 0;
  background-image: url("images/aths-background.jpg");
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  position: relative;
}

/* Add another pseudo-element ::after to place the logo in the upper right corner */
body::before {
  content: "";
  position: absolute;
  top: 80px; /* Adjust the top position as needed to place the logo */
  left: 150px; /* Adjust the right position as needed to place the logo */
  width: 450px; /* Adjust the width of the logo as needed */
  height: 450px; /* Adjust the height of the logo as needed */
  background-image: url("images/athsss.png"); /* Set the path to your logo image with a transparent background */
  background-size: contain; /* Adjust the background-size property as needed */
  background-repeat: no-repeat;
  background-color: transparent; /* Ensure no background color is applied */
}


/* Add a pseudo-element ::before to create the gradient overlay */
body::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom,  #e1ad01, white, #356afc);
  opacity: .85;
}

        #digital-clock {
            font-family: 'Arial', sans-serif;
            font-size: 48px;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            position: absolute;
            top: 20px;
            right: 20px;
        }
        table{
            top: 600px;
            display: absolute;
            border-collapse: separate;
            width: 100%;
            font-size: 20px;
            border-spacing: 50px 50px;
        }
        th {
            top: 575px;
            color: white;
            width: 200px;
            background-color: rgba(0, 0, 0, .6); /* Translucent black with 60% opacity */
            position: relative;
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.3); /* Horizontal offset, vertical offset, blur radius, color */
        }

        table .fa-solid {
            margin-bottom: 10px;
            color: #fff566; /* Neon yellow color */
            text-shadow: 0 0 10px rgba(255, 245, 102, 0.7); /* Neon glow effect */
            font-size: 4rem;
        }
        .mantra {
            color: white;
            font-family: Impact, fantasy, sans-serif;
            font-weight: 100;
            position: absolute;
            top: 120px;
            font-size: 80px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        .mantra p{
            font-family: Impact, fantasy, sans-serif;
            font-weight: 100;
            font-size: 34px;
            margin-top: 25px;
        }

        span{
            font-size: 50px;
        }
    </style>
</head>
<body>
    <div class="white-background"></div>

    <div class="home-section">
        <div class="home-content">
            <div class="mantra">
            FOLLOWING JESUS, WE LIVE SYNODALITY AND RESPOND TO OUR NEW REALTIES TODAY
            <p>SCHOOL YEAR 2023 – 2024</p>
            </div>
            <table>
                <tr>
                    <th>
                        <i class="fa-solid fa-users"></i><br>
                        <span><?php print $total_active ?></span><br>
                        <label>ACTIVE</label><br>
                    </th>
                    <th>
                        <i class="fa-solid fa-user-slash"></i><br>
                        <span><?php print $total_resigned ?></span><br>
                        <label>RESIGNED</label><br>
                    </th>

                    <th>
                        <i class="fa-solid fa-user-lock"></i><br>
                        <span><?php print $total_permanent ?></span><br>
                        <label>PERMANENT</label><br>
                    </th>
                    <th>
                        <i class="fa-solid fa-magnifying-glass"></i><br>
                        <span><?php print $total_probationary ?></span><br>
                        <label>PROBATIONARY</label><br>
                    </th>

                    <th>
                        <i class="fa-solid fa-mars"></i><br>
                        <span><?php print $total_male ?></span><br>
                        <label>MALE</label><br>
                    </th>
                    <th>
                        <i class="fa-solid fa-venus"></i><br>
                        <span><?php print $total_female ?></span><br>
                        <label>FEMALE</label><br>
                    </th>
                </tr>
            </table>
        </div>    
    </div>
    <div id="digital-clock"></div>
    <div>
        <?php 
        $timezone = new DateTimeZone('Asia/Manila');
        $resignationDateObj = new DateTime();
        $resignationDateObj->setTimezone($timezone);
        //echo "------------------------------------------------" .$resignationDateObj->format('Y-m-d');
        ?>
    </div>

    
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