<?php 
    session_start();

    // Redirect to the admin dashboard page if user is not authenticated
    if (!isset($_SESSION['user'])) {
        header("Location: login_hris.php");
        exit();
    }

    include_once 'navbar_hris.php';
    change_default();

    // Check if 'control' parameter is provided in the URL
    if (isset($_GET['control'])) {
        $control_number = $_GET['control'];
    }else{
        echo '<script>
            alert("Please Select Employee.");
            window.location="cur_emp.php";
            </script>';
        exit;
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

        $sql = "DELETE FROM attendance WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $stmt->close();

        $sql = "DELETE FROM service_record WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $stmt->close();

        $sql = "DELETE FROM user WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
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

    function getMonths() {
        global $conn;
        $query = "SELECT * FROM attendance_year";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $months = explode(", ", $row['months']);
        }

        return $months;
    }

    function getTotal($conn, $control_number) {
        $months = getMonths();
        $totalData = array(
            'absent' => array(),
            'late' => array(),
            'undertime' => array()
        );
    
        foreach ($months as $month) {
            $sql = "SELECT SUM(absent) AS total_absent, SUM(late) AS total_late, SUM(undertime) AS total_undertime 
                    FROM attendance WHERE control_number = ? AND month = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $control_number, $month);
            $stmt->execute();
            $innerResult = $stmt->get_result();
    
            if ($innerResult && $innerResult->num_rows > 0) {
                $attendance = $innerResult->fetch_assoc();
    
                $totalData['absent'][$month] = $attendance['total_absent'];
                $totalData['late'][$month] = $attendance['total_late'];
                $totalData['undertime'][$month] = $attendance['total_undertime'];
            }
            
            $stmt->close();
        }
    
        return $totalData;
    }    

    $totalData = getTotal($conn, $control_number);
    $totalAbsent = array_sum($totalData['absent']);
    $totalLate = array_sum($totalData['late']);
    $totalUndertime = array_sum($totalData['undertime']);

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
            height: 900px;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin-left: 420px;
            border: 1px solid black;
            overflow: auto;
            margin-top: -40px;
        }

        a {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 16px;
            margin-top: -1rem;
        }

        th, td {
            display: relative;
            text-align: left;
            padding: 8px;
            border: 1px solid black;
            white-space: nowrap;
            height: 0px;
        }
        td {
            vertical-align: top; /* Align text to the top vertically */
        }
        #personal_td {
            vertical-align: center; 
        }

        .profile1 {
            min-width: 230px;
            min-height: 230px;
            max-width: 230px;
            max-height: 230px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .center_profile {
            text-align: center;
            
        }

        .name { //!unused 
            font-size: 0px;
        }

        .details { //!unused
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
            margin-bottom: -100px;
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

        table.attendance {
            border-collapse: collapse;
            width: 100%;
            font-size: 14px;
            margin-top: 20px;
        }
        table.attendance th, table.attendance td {
            display: relative;
            text-align: center;
            padding: 1px 4px 1px 4px;
            border: 1px solid black;
            white-space: nowrap;
            height: auto;
        }
        table.attendance th {
            background: bisque;
            text-align: center;
        }
        #address {
            min-width: 450px;
            resize: none;
            border: none;
            font-size: 16px;
        }

        h2 {
            font-weight: 550;
        }
        .bold {
            font-weight: bold;
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
                            $fontColor = ($employeeStatus === 'active') ? 'black' : 'red';
                            $fullName = $row['surname'] . ", " . $row['name'];
                            if ($row['middle_name'] != "") {
                                $fullName .= " " . $row['middle_name'];
                            }
                            if ($row['suffix'] != "") {
                                $fullName .= " " . $row['suffix'];
                            }

                            echo '<td class="center_profile"><img src="images/' . $row['image'] . '" alt="avatar" class="profile1"><br>';
                            
                            echo (isset($row['control_number'])) ? "<span style='font-size: 16px;'>" . $row['control_number'] . "</span><br>" : "Not Applicable";
                            //echo "<span style='font-size: 16px;'>" . $row['control_number'] . "</span><br>";
                            echo "</td><td id='personal_td'>";
                            
                            echo "<span style='color: $fontColor; font-size: 30px; font-weight: bold;'>$fullName</span><br>";
                            
                            echo "<span class='bold'>Birthday: </span>" . $row['birthday'] . " (" . $row['age'] . ")" ."<br>";
                            //echo "age: " . $row['age'];
                            echo "<span class='bold'> Civil Status: </span>" . (($row['civil_status'] == 'single') ? 'Single' : (($row['civil_status'] == 'married') ? 'Married' : (($row['civil_status'] == 'widowed') ? 'Widowed' : (($row['civil_status'] == 'separated') ? 'Separated' : (($row['civil_status'] == 'annulled') ? 'Annulled' : 'Not Applicable'))))) . "<br>";
                            //echo "Civil Status: " . $row['civil_status'] . "<br>";
                            
                            echo "<span class='bold'>Gender: </span>" . $row['gender'] . "<br>";
                            
                            echo "<span class='bold'>Address: </span><br>";
                            echo '<textarea name="address" id="address" readonly>' . (($row['address'] != '') ? htmlspecialchars($row['address']) : 'Not Applicable') . '</textarea><br>';
                            //echo '<textarea name="address" id="address" readonly>' . htmlspecialchars($row['address']) . '</textarea><br>';

                            echo "<span class='bold'>Contact: </span>" . (($row['contact'] != "") ? $row['contact'] : "Not Applicable") . "<br>";
                            //echo "Contact: " . $row['contact'] . "<br>";
                            echo "<span class='bold'>Email: </span>" . (($row['email'] != "") ? $row['email'] : "Not Applicable") . "<br>";
                            //echo "Email: " . $row['email'] . "<br>";
                            echo "</td><tr><td>";

                            echo "<h2>Employment Information</h2>";
                            if ($employeeStatus === 'resigned') {
                                
                                echo "<span style='color: red'> RESIGNED on " . $row['resignation_date'] . "</span><br>";
                                echo "<span class='bold'>Employment Status: </span>";
                                echo $row['employment_status'];
                            } else {
                                
                                echo "<span class='bold'>Employment Status: </span>";
                                echo $row['employment_status'];
                            }
                            
                            echo "<br><span class='bold'>Classification: </span>" . $row['classification'] . "<br>";
                            
                            echo "<span class='bold'>Department: </span>" . $row['department'] . "<br>";
                            echo "<span class='bold'>Position: </span>" . (($row['position'] != "") ? $row['position'] : "Not Applicable") . "<br>";
                            //echo "Position: " . $row['position'] . "<br>";
                            echo "<span class='bold'>Date Hired: </span>" . (($row['date_hired'] != "") ? $row['date_hired'] : "Not Applicable") . "<br>";
                            
                            //echo "Date Hired: " . $row['date_hired'] . "<br>";
                            echo "<span class='bold'>Years in Service: </span>" . (($row['years_in_service'] != "") ? $row['years_in_service'] : "Not Applicable") . "<br>";
                            //echo "Years in Service: " . $row['years_in_service'] . "<br>";
                            echo "</td><td>";

                            
                            echo "<h2>Educational Background</h2>";
                            echo "<span class='bold'>Course Taken: </span>" . (($row['course_taken'] != "") ? $row['course_taken'] : "Not Applicable") . "<br>";
                            //echo "Course Taken: " . $row['course_taken'] . "<br>";
                            echo "<span class='bold'>Further Studies: </span>" . (($row['further_studies'] != "") ? $row['further_studies'] : "Not Applicable") . "<br>";
                            //echo "Further Studies: " . $row['further_studies'] . "<br>";
                            echo "<span class='bold'>Number of Units: </span>" . (($row['number_of_units'] != "0") ? $row['number_of_units'] : "Not Applicable") . "<br>";
                            //echo "Number of Units: " . $row['number_of_units'] . "<br>";
                            echo "<span class='bold'>PRC Number: </span>" . (($row['prc_number'] != "") ? $row['prc_number'] : "Not Applicable") . "<br>";
                            //echo "PRC Number: " . $row['prc_number'] . "<br>";
                            echo "<span class='bold'>PRC Expiration: </span>" . (($row['prc_exp'] != "0000-00-00") ? $row['prc_exp'] : "Not Applicable") . "<br>";
                            //echo "PRC Expiration: " . $row['prc_exp'] . "<br>";
                            echo "</td></tr><td>";

                            echo "<h2>Attendance</h2>";
                            
                            echo "<span class='bold'>Total Absent: </span>" . $totalAbsent . "<br>";
                            
                            echo "<span class='bold'>Total Late: </span>" . $totalLate . "<br>";
                            
                            echo "<span class='bold'>Total Undertime: </span>" . $totalUndertime . "<br>";
                            
                            echo "<span class='bold'>Total Leave: </span>" . $row['vl'] + $row['sl'] . "<br>";
                            
                            echo "<span class='bold'>Remaining Leave: </span>" . (($row['remaining_leave'] > 0) ? $row['remaining_leave'] : (($row['remaining_leave'] == 0) ? abs($row['remaining_leave']) : "(" . $row['remaining_leave'] . ")")) . "<br>";
                            echo "</td><td>";

                            echo "<h2>Other Information</h2>";
                            if ($_SESSION['access_level'] == 'super admin') {
                                echo "<span class='bold'>Salary: </span>" . (($row['date_hired'] != "") ? "18,000" : "Not Applicable") . "<br>";
                            }
                            echo "<span class='bold'>Tin: </span>" . (($row['tin'] != "") ? $row['tin'] : "Not Applicable") . "<br>";
                            //echo "TIN: " . $row['tin'] . "<br>";
                            echo "<span class='bold'>SSS: </span>" . (($row['sss'] != "") ? $row['sss'] : "Not Applicable") . "<br>";
                            //echo "SSS: " . $row['sss'] . "<br>";
                            echo "<span class='bold'>PHILHEALTH: </span>" . (($row['philhealth'] != "") ? $row['philhealth'] : "Not Applicable") . "<br>";
                            //echo "PHILHEALTH: " . $row['philhealth'] . "<br>";
                            echo "<span class='bold'>PAG-IBIG: </span>" . (($row['pag_ibig'] != "") ? $row['pag_ibig'] : "Not Applicable") . "<br>";
                            //echo "PAG-IBIG: " . $row['pag_ibig'] . "<br>";
                            echo "</td>";

                            
                        }
                        
                    }
                ?>
            </tr>
        </table>
        <?php 
            $months = getMonths();
            $length = count($months) + 1;
            $lastMonth = end($months);
            $firstMonth = reset($months);
            $i = 0;

            $totalAbsent = 0;
            $totalLate = 0;
            $totalUndertime = 0;
            echo "<table class='attendance'>";
            echo "<tr><th colspan='$length'>Attendance</th></tr>";
            echo "<th></th>";
            foreach ($months as $month) {
                $abbreviatedMonth = substr($month, 0, 3); // Extract the first 3 characters of the month
                echo "<th>$abbreviatedMonth</th>";
            }            
            echo "<tr>";
            for ($i = 0; $i < 3; $i++) {
                foreach ($months as $month) {
                    if ($month == $firstMonth) {
                        if ($i == 0) {
                            echo "<th>Absent</th>";
                        }
                        elseif ($i == 1) {
                            echo "</tr><tr><th>Late</th>";
                        }
                        elseif ($i == 2) {
                            echo "</tr><tr><th>Undertime</th>";
                        }
                    }
                    $sql = "SELECT * FROM attendance WHERE control_number = ? AND month = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $control_number, $month);
                    $stmt->execute();
                    $innerResult = $stmt->get_result();
                    $stmt->close();

                    if ($innerResult && $innerResult->num_rows > 0) {
                        $attendance = $innerResult->fetch_assoc();
                        if ($attendance['month'] == $month && $i == 0) {
                            echo "<td>" . $attendance['absent'] . "</td>";
                            $totalAbsent += $attendance['absent'];
                        }
                        if ($attendance['month'] == $month && $i == 1) {
                            echo "<td>" . $attendance['late'] . "</td>";
                            $totalLate += $attendance['late'];
                        }
                        if ($attendance['month'] == $month && $i == 2) {
                            echo "<td>" . $attendance['undertime'] . "</td>";
                            $totalUndertime += $attendance['undertime'];
                        }
                        
                    }
                    else {
                        echo "<td>0</td>";
                    }
                }
            }
            echo "</table>";
        ?>
        <?php 

            echo "<div style='margin-top: 20px;'>";
            if ($user['status'] == 'resigned' && $_SESSION['access_level'] != 'employee') {
                echo '<a class="submit-button" href="resigned.php" id="back">Back</a>';
            } elseif ($user['status'] == 'active' && $_SESSION['access_level'] != 'employee') {
                echo '<a class="submit-button" href="cur_emp.php" id="back">Back</a>';
            }
            if ($_SESSION['access_level'] != 'employee'){
                echo '<a class="submit-button" href="edit_info.php?control=' . $user['control_number'] . '">' . 'Edit Information</a>';
            }
            echo '<a class="submit-button" href="add_service_record.php?control=' . $user['control_number'] . '">' . 'Service Record</a>';
            echo '<form method="post" onsubmit="return confirm(\'Are you sure you want to delete this record?\')">';
            echo '<input type="hidden" name="employeeStatus" value="' . $employeeStatus . '">';
            if ($_SESSION['access_level'] != 'employee'){
            echo '<input type="submit" class="submit-button" name="delete" id="delete" value="Delete">';
            }
            echo '</form>';
            echo "</div>";
        ?>
    </div>
</body>
</html>
