<?php 
    session_start();

    // Redirect to the admin dashboard page if user is not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: login_hris.php");
        exit();
    }

    include 'navbar_hris.php';

    // Check if 'control' parameter is provided in the URL
    if (isset($_GET['control'])) {
        $control_number = $_GET['control'];
    }

    // Check if the delete form is submitted
    if (isset($_POST['delete'])) {
        // Database connection check
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the image filename from the database
        $imageFilename = ""; // Set the default filename
        $getImageSql = "SELECT image FROM employees WHERE control_number = ?";
        $stmtImage = $conn->prepare($getImageSql);
        $stmtImage->bind_param("s", $control_number);
        $stmtImage->execute();
        $stmtImage->bind_result($imageFilename);
        $stmtImage->fetch();
        $stmtImage->close();

        // Delete the image from the folder
        if (!empty($imageFilename) && $imageFilename != "default.jpg") {
            $imagePath = "images/" . $imageFilename;
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image file
            }
        }
        
        // Sample delete SQL query (modify according to your database schema)
        $deleteSql = "DELETE FROM employees WHERE control_number = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $stmt->close();

        // Redirect based on employee status
        $type = "Employee Deleted";
        history($_SESSION['control_number'], $control_number, $type);
        $emp_status = $_POST['employeeStatus'];
        if ($emp_status === 'active') {
            echo '<script>window.location.href = "cur_emp.php";</script>';
        } elseif ($emp_status === 'resigned') {
            echo '<script>window.location.href = "resigned.php";</script>';
        }
    }
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
            background-attachment: fixed;
        }
        .container {
            background-color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 1rem;
            width: 60%;
            height: 1200px;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: auto;
            border: 0.5px solid black;
            overflow: auto;
        }

        a {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 16px;
            
        }

        th, td {
            display: relative;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            white-space: nowrap;
            height: 0px;
        }

        .profile1 {
            min-width: 200px;
            min-height: 200px;
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .center_profile {
            text-align: center;
            max-width: 200px;
        }

        .name {
            font-size: 40px;
        }

        .details {
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }

        .submit-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            width: 170px;
        }
        form {
            display: inline-block;
        }

        .submit-button:hover {
            background-color: #0056b3;
        }
        

        .submit-button:active {
            background-color: #003974;
        }

        #back {
            width: 120px;
            background-color: #4CAF50;
        }

        #back:hover {
            background-color: #3a8a3d;
        }

        #delete {
            width: 120px;
            background-color: #fc0303;
        }

        #delete:hover {
            background-color: #9c0402;
        }
    </style>
</head>
<body>
    <div class="container">
        <table>
            <tr>
                <?php 
                    if (isset($_GET['control'])) {
                        $sql = "SELECT * FROM employees WHERE control_number = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $control_number);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                            $user = $row;
                            $employeeStatus = $row['status'];
                            $fontColor = ($employeeStatus === 'active') ? 'green' : 'red';
                            $fullName = $row['surname'] . ", " . $row['name'] . " " . $row['middle_name'];

                            echo '<tr><td class="center_profile" colspan="2"><img src="images/' . $row['image'] . '" alt="avatar" class="profile1">';
                            echo "<h1>";
                            echo "<span style='color: $fontColor'>$fullName</span>";
                            echo "</h1></td></tr><tr><td colspan='2'>";
                            echo "<h2>Personal Information</h2>";
                            echo "Control Number: " . $row['control_number'] . "<br>";
                            echo "Birthday: " . $row['birthday'] . "<br>";
                            echo "Civil Status: " . $row['civil_status'] . "<br>";
                            echo "Gender: " . $row['gender'] . "<br>";
                            echo "Address: " . $row['address'] . "<br>";
                            echo "Contact: " . $row['contact'] . "<br>";
                            echo "Email: " . $row['email'] . "<br>";
                            echo "</td></tr><tr><td colspan='2'>";

                            echo "<h2>Employment Information</h2>";
                            echo "<p>";
                            echo "Employment Status: ";
                            if ($employeeStatus === 'resigned') {
                                echo $row['employment_status'];
                                echo "<span style='color: red'> RESIGNED on " . $row['resignation_date'] . "</span>";
                            } else {
                                echo $row['employment_status'];
                            }
                            echo "</p>";
                            echo "Classification: " . $row['classification'] . "<br>";
                            echo "Date Hired: " . $row['date_hired'] . "<br>";
                            echo "Years in Service: " . $row['years_in_service'] . "<br>";
                            echo "</td></tr><td colspan='2'>";

                            echo "<h2>Educational Background</h2>";
                            echo "Course Taken: " . $row['course_taken'] . "<br>";
                            echo "Further Studies: " . $row['further_studies'] . "<br>";
                            echo "Number of Units: " . $row['number_of_units'] . "<br>";
                            echo "PRC Number: " . $row['prc_number'] . "<br>";
                            echo "PRC Expiration: " . $row['prc_exp'] . "<br>";
                            echo "Position: " . $row['position'] . "<br>";
                            echo "</td></tr><tr><td colspan='2'>";

                            echo "<h2>Other Information</h2>";
                            echo "TIN: " . $row['tin'] . "<br>";
                            echo "SSS: " . $row['sss'] . "<br>";
                            echo "PHILHEALTH: " . $row['philhealth'] . "<br>";
                            echo "PAG-IBIG: " . $row['pag_ibig'] . "<br>";
                            echo "</td></tr>";
                        }
                    }
                ?>
            </tr>
        </table>
        
        <?php 
            echo "<div>";
            if ($user['status'] == 'resigned') {
                echo '<a class="submit-button" href="resigned.php" id="back">Back</a>';
            } elseif ($user['status'] == 'active') {
                echo '<a class="submit-button" href="cur_emp.php" id="back">Back</a>';
            }
            echo '<a class="submit-button" href="edit_info.php?control=' . $user['control_number'] . '">' . 'Edit Information</a>';
            echo '<a class="submit-button" href="add_service_record.php?control=' . $user['control_number'] . '">' . 'Service Record</a>';
            echo '<form method="post" onsubmit="return confirm(\'Are you sure you want to delete this record?\')">';
            echo '<input type="hidden" name="employeeStatus" value="' . $employeeStatus . '">';
            echo '<input type="submit" class="submit-button" name="delete" id="delete" value="Delete">';
            echo '</form>';
            echo "</div>";
        ?>
    </div>
</body>
</html>
