<?php 
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
    header("Location: login_hris.php");
    exit();
}
include 'navbar_hris.php';
change_default();
if (isset($_GET['control'])) {
    $control_number = $_GET['control'];
}

$search_name = isset($_GET['name']) ? $_GET['name'] : '';
if (isset($control_number) && $search_name == '' && isset($_POST['search'])) {
    echo "<script>
    window.location.href='attendance.php?name=" . urlencode($_POST['name']) . "';
    </script>";
}

$sql = "SELECT * FROM attendance_year ORDER BY school_year DESC"; // Order by descending to get the latest year first
$result = $conn->query($sql);

$years = array(); // Array to store years
if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $years[] = $row['school_year'];
    }
}

// Set the default year as the latest year
$defaultYear = $years[0]; // The first year in the array is the latest year

function checkForChanges($conn, $control_number, $months)
{
    $changes = array();

    foreach ($months as $month) {
        $absent = isset($_POST["absent_$month"]) ? ($_POST["absent_$month"] == null ? 0 : $_POST["absent_$month"]) : 0;
        $late = isset($_POST["late_$month"]) ? ($_POST["late_$month"] == null ? 0 : $_POST["late_$month"]) : 0;
        $undertime = isset($_POST["undertime_$month"]) ? ($_POST["undertime_$month"] == null ? 0 : $_POST["undertime_$month"]) : 0;

        // Retrieve data from the database for the current month
        $existingQuery = "SELECT * FROM attendance WHERE control_number = ? AND month = ?";
        $existingStmt = $conn->prepare($existingQuery);
        $existingStmt->bind_param("ss", $control_number, $month);
        $existingStmt->execute();
        $existingResult = $existingStmt->get_result();

        if ($existingResult->num_rows > 0) {
            $row = $existingResult->fetch_assoc();

            // Compare the database values with the submitted values
            if (
                $row['absent'] != $absent ||
                $row['late'] != $late ||
                $row['undertime'] != $undertime
            ) {
                $changes[$month] = array(
                    'absent' => $absent,
                    'late' => $late,
                    'undertime' => $undertime,
                );
            }
        }
    }

    return $changes;
}


if (isset($_POST['updateAttendance'])) {
    $monthsQuery = "SELECT months FROM attendance_year LIMIT 1";
    $monthsResult = $conn->query($monthsQuery);
    $row = $monthsResult->fetch_assoc();
    $months = explode(", ", $row['months']);

    $flag = false;
    foreach ($months as $month) {
        $absent = isset($_POST["absent_$month"]) ? ($_POST["absent_$month"] == null ? 0 : $_POST["absent_$month"]) : 0;
        $late = isset($_POST["late_$month"]) ? ($_POST["late_$month"] == null ? 0 : $_POST["late_$month"]) : 0;
        $undertime = isset($_POST["undertime_$month"]) ? ($_POST["undertime_$month"] == null ? 0 : $_POST["undertime_$month"]) : 0;

        // Fetch the values for the current month from the database
        $existingQuery = "SELECT * FROM attendance WHERE control_number = ? AND month = ?";
        $existingStmt = $conn->prepare($existingQuery);
        $existingStmt->bind_param("ss", $control_number, $month);
        $existingStmt->execute();
        $existingResult = $existingStmt->get_result();

        if ($existingResult->num_rows > 0) {
            $row = $existingResult->fetch_assoc();

            // Compare the database values with the submitted values
            if (
                $row['absent'] != $absent ||
                $row['late'] != $late ||
                $row['undertime'] != $undertime
            ) {
                $updateQuery = "UPDATE attendance SET absent = ?, late = ?, undertime = ? WHERE control_number = ? AND month = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ddsss", $absent, $late, $undertime, $control_number, $month);
                $updateStmt->execute();
                $updateStmt->close();
                $flag = true;
                //@ shall i include the delete?
                if ($absent == 0 && $late == 0 && $undertime == 0) {
                    // Delete the record if all values are 0
                    $deleteQuery = "DELETE FROM attendance WHERE control_number = ? AND month = ?";
                    $deleteStmt = $conn->prepare($deleteQuery);
                    $deleteStmt->bind_param("ss", $control_number, $month);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                    $flag = true;
                }
            }
        } else {
            // Insert new records for the current month if they don't exist
            if ($absent != 0 || $late != 0 || $undertime != 0) {
                $insertQuery = "INSERT INTO attendance (control_number, month, absent, late, undertime) VALUES (?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("ssddd", $control_number, $month, $absent, $late, $undertime);
                $insertStmt->execute();
                $insertStmt->close();
                $flag = true;
            }
        }
    }
    
    if ($flag) {
        // Redirect or display a success message
        if (isset($control_number) && $search_name != '' ) {
            echo "<script>
            alert('Successfully Updated Attendance.');
            window.location.href='attendance.php?name=" . urlencode($search_name) . "';
            </script>";
            exit;
        } else {
            echo "<script>
            alert('Successfully Updated Attendance.');
            window.location.href='attendance.php';
            </script>";
            exit;
        }
    } else {
        echo "<script>
        alert('No changes made.');
        window.location.href='attendance.php';
        </script>";
        exit;
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
        }

        .container {
            background-color: #fff;
            border: 1px solid grey;
            border-radius: 5px 0 0 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: 20px auto 0;
            max-height: 780px;
            overflow-y: auto;
            width: 95%;grey
        }

        .container::-webkit-scrollbar {
            width: 10px;
            height: 15px;
        }

        .container::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 5px;
        }

        .container::-webkit-scrollbar-track {
            background-color: transparent;
        }
        table {
            border-collapse: collapse;
            width: auto;
            font-size: 14px;
        }

        th, td {
            display: relative;
            text-align: center;
            padding: 4px 8px 4px 8px;
            border: 1px solid black;
            white-space: nowrap;
            height: auto;
        }

        th{
            background: bisque;
            text-align: center;
            min-width: 40px;
        }
        #labels {
            width: 10%; 
        }
        #name_td {
            text-align: left;
        }
        #department_td {
            text-align: left;
            font-weight: bold;
            font-size: 14px;
            background-color: #4CAF50;
            padding: 3px 8px 3px 8px;
        }
        .total_td {
            background-color: #4CAF50;
        }
        .abs_td {
            background-color: yellow;
        }
        th#abs_td_size{
            padding: 5px;
        }
        th #month{
            width: 150px; /* Adjust the width as needed */
        }
        .form1 td{
            border: none;
        }

        .fixed-row {
            position: sticky;
            top: 0;
            background-color: #f0f0f0;
            z-index: 1000;
        }

        .fixed-row1 {
            position: sticky;
            top: 30px;
            background-color: #f0f0f0;
            z-index: 1000;
            
        }

        .scrollable-content {
            /* Add appropriate padding to the top to prevent content from being hidden behind the fixed row */
            padding-top: /* height of the fixed row */;
        }
        .form1{
            background-color: rgba(255, 255, 255, 0.45); /* 0.9 alpha for 90% opacity */
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            display: relative;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            width: 95%;
            margin: auto;
            margin-top: -20px;
            font-weight: bold;
            overflow: hidden;
        }
        .highlighted-row {
            background-color: #5cabff;
        }
        .highlighted-cell {
            background-color: #5cabff;
        }
        td a {
            text-decoration: none;
            color: #000; /* Change the color to your desired color */
        }
        td #click:hover {
            cursor: pointer;
        }
        button {
            display: inline-block;
            padding: 5px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        #name{
            width: 203px;
        }
        #years_service{
            width: 55px;
        }
        #civil_status{
            width: 140px;
        }
        #gender{
            width: 115px;
        }
        #control_number{
            width: 178px;
        }
        .search-bar{
            width: 1000px;
        }
        td a {
            text-decoration: none;
            color: #000;
        }

        td a:hover{
            color: #0056b3;
            font-weight: bold;
        }

        td #click:hover {
            cursor: pointer;
        }
        td input {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        td.input_td, td.abs_td {
            padding: 1px;
        }
        #update {
            border: none;
            cursor: pointer;
        }
        #cancel {
            background-color: #fc0303;
            border: none;
            cursor: pointer;
        }
        #update, #cancel {
            padding: 5px 10px;
            margin: 0 5px;
            border-radius: 5px;
            color: #fff;
            font-size: 14px;
        }
        #cancel:hover {
            background-color: #9c0402;
        }
        
    </style>
    
</head>
<body>
    <div class="home-section">
        <div class="home-content">
        <form class="form1" action="" method="POST">
            <input type="hidden" name="previous_school_year" value="<?php echo isset($_POST['school_year']) ? $_POST['school_year'] : ''; ?>">
            <table class="search-bar">
            <tr>
            <td colspan="2" rowspan="2"><h1>Attendance</h1></td>
            <td><td></td></td>
            <td><td></td></td>
            <td><td></td></td>
            <td>
            <label for="name">Search Name:</label>
            <input type="text" name="name" id="name" placeholder="Search Name" value="<?php echo isset($_GET['name']) ? htmlspecialchars($_GET['name']) : (isset($_POST['name']) ? $_POST['name'] : ''); ?>">
            </td>
            <td rowspan="2"><button type="submit" name="search">Search</button>
            <button type="reset" onclick="resetSearchInput()">Reset</button>
            <button type="button" onclick="redirectToAddSchoolYear()">Add School Year</button>
            </td>
            </tr>
            <tr>
            <td><td></td></td>
            <td><td></td></td>
            <td><td></td></td>
            </tr>
            </table>
            

            
        </form>
        <form action="" method="POST">
        <div class="container">
            <table>
            <thead>

                <tr class="fixed-row">
                    
                    
                    <?php 
                        $selectedSchoolYear = isset($_POST['school_year']) ? $_POST['school_year'] : $defaultYear;
                        $query = "SELECT * FROM attendance_year";
                        $stmt = $conn->prepare($query);
                        $stmt->execute();
                        
                        $result = $stmt->get_result();
                        
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $months = explode(", ", $row['months']);
                            echo "<th colspan='2'>" . $row['school_year'] . "</th>";
                            // First row with month headers
                            foreach ($months as $month) {
                                echo "<th colspan='3' id='month'>$month</th>";
                            }
                            if (!isset($control_number)) {
                                echo "<th colspan='3'>TOTAL</th>";
            
                                echo "<th>COMBINED</th>";
                                echo "<th>LATE & U.T.</th>";
                                echo "<th colspan='2'>TOTAL</th>";
                                echo "<th>REMAINING</th>";
                                echo "</tr>"; // Close the first row
                            }else{
                                echo "<th rowspan='2'>UPDATE</th>";
                            }
                                echo "<tr class='fixed-row1'>"; // Start a new row
                                echo "<th>#</th>";
                                echo "<th >Name</th>";
                                // Second row with attendance categories
                                foreach ($months as $month) {
                                    echo "<th class='abs_td'>ABS</th>";
                                    echo "<th id='abs_td_size'>LATE</th>";
                                    echo "<th>U.T.</th>";
                                }
                            if (!isset($control_number)) {
                                echo "<th class='total_td'>ABS</th>";
                                echo "<th id='abs_td_size'>LATE</th>";
                                echo "<th>U.T.</th>";
                                echo "<th>LATE & U.T.</th>";
                                echo "<th>CONVERSION</th>";
                                echo "<th class='total_td'>ABS. & LATE</th>";
                                echo "<th>VL & SL</th>";
                                echo "<th>VL & SL</th>";
                                echo "</tr>"; // Close the second row
                            }
                            
                            
                        }else{
                            echo "<tr>";
                            echo "<td>Select School Year</td>";
                            echo "</tr>";
                        }
                    ?>
                </tr>
            </thead>

            <?php 
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $departmentOrder = array(); // Initialize an empty array to store department values

                $sql = "SELECT * FROM data_values WHERE data_type = 'department' ORDER BY value_id ASC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $departmentOrder[] = $row['data_value'];
                    }
                }
                
                $sql = "SELECT * FROM employees WHERE status = 'active' ";

                // Check if search input is provided
                if (isset($_POST['name']) && !empty($_POST['name']) || isset($search_name) && !empty($search_name)) {
                    $search_name = isset($_POST['name']) ? $_POST['name'] : $search_name;
                    // Add all possible combinations of name search to the SQL query
                    $sql .= " AND (CONCAT(name, ' ', middle_name, ' ', surname) LIKE '%$search_name%'
                    OR CONCAT(surname, ' ', name, ' ', middle_name) LIKE '%$search_name%'
                    OR CONCAT(surname, ' ', name) LIKE '%$search_name%'
                    OR CONCAT(name, ' ', surname) LIKE '%$search_name%'
                    OR CONCAT(middle_name, ' ', name) LIKE '%$search_name%'
                    OR CONCAT(middle_name, ' ', surname) LIKE '%$search_name%'
                    OR CONCAT(surname, ' ', middle_name) LIKE '%$search_name%')";
                }
                
                $sql .= " ORDER BY FIELD(department, '" . implode("', '", $departmentOrder) . "')";
                $result = $conn->query($sql);
                $i = 1;
                if ($result->num_rows > 0) {
                    echo '<tbody class="scrollable-content">';
                    $prevDepartment = ''; // To keep track of the previous department
                    while ($row = mysqli_fetch_assoc($result)) {
                        $department = $row['department'];

                        // Print department name if it's different from the previous department
                        if ($department != $prevDepartment) {
                            echo "<tr><td colspan='100%' id='department_td'>$department</td></tr>";
                            $prevDepartment = $department;
                            $i = 1;
                        }
                        $fname = $row['surname'] . ', ' . $row['name'];
                
                        if ($row['middle_name'] !== "") {
                            $fname .= ' ' . $row['middle_name'][0] . '.';
                        }
                        if ($row['suffix'] !== "") {
                            $fname .= ' ' . $row['suffix'];
                        }
                
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td> $i </td>";
                        echo '<td id="name_td"><a href="attendance.php?control=' . $row['control_number'] . '&name=' . (isset($_POST['name']) ? urlencode($_POST['name']) : (isset($search_name) ? $search_name : "")) . '">' . $fname . '</a></td>';
                        $totalAbsent = 0;
                        $totalLate = 0;
                        $totalUndertime = 0;
                        foreach ($months as $month) {
                            $sql = "SELECT * FROM attendance WHERE control_number = ? AND month = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss", $row['control_number'], $month);
                            $stmt->execute();
                            $innerResult = $stmt->get_result();
                            $stmt->close();
                            if ($innerResult && $innerResult->num_rows > 0) {
                                $attendance = $innerResult->fetch_assoc();
                                if (isset($control_number) && $control_number == $row['control_number']) {
                                    echo "<td class='abs_td'>
                                            <input type='number' name='absent_$month' min='0' value='" . ($attendance['absent'] != 0 ? $attendance['absent'] : "") . "'>
                                        </td>";
                                    echo "<td class='input_td'>
                                            <input type='number' name='late_$month' min='0' value='" . ($attendance['late'] != 0 ? $attendance['late'] : "") . "'>
                                        </td>";
                                    echo "<td class='input_td'>
                                            <input type='number' name='undertime_$month' min='0' value='" . ($attendance['undertime'] != 0 ? $attendance['undertime'] : "") . "'>
                                        </td>";

                                }else{
                                    echo "<td class='abs_td'>" . ($attendance['absent'] != 0 ? $attendance['absent'] : "") . "</td>";
                                    echo "<td>" . ($attendance['late'] != 0 ? $attendance['late'] : "") . "</td>";
                                    echo "<td>" . ($attendance['undertime'] != 0 ? $attendance['undertime'] : "") . "</td>";
                                    $totalAbsent += $attendance['absent'];
                                    $totalLate += $attendance['late'];
                                    $totalUndertime += $attendance['undertime'];
                                }
  
                            } else {
                                if (isset($control_number) && $control_number == $row['control_number']) {
                                    echo "<td class='abs_td'' ><input type='number' name='absent_$month' min='0'></td>";
                                    echo "<td class='input_td'><input type='number' name='late_$month' min='0'></td>";
                                    echo "<td class='input_td'><input type='number' name='undertime_$month' min='0'></td>";
                                }
                                else{
                                    echo "<td class='abs_td'></td>";
                                    echo "<td></td>";
                                    echo "<td></td>";   
                                } 
                            }
                            
                        }
                        if (!isset($control_number)) {
                            echo "<td class='total_td'>$totalAbsent</td>";
                            echo "<td>$totalLate</td>";
                            echo "<td>$totalUndertime</td>";

                            $combined = $totalLate + $totalUndertime;
                            echo "<td>$combined</td>";

                            $conversion = $combined / 60 / 8;
                            $roundedConversion = number_format($conversion, 2);
                            echo "<td>" . ($roundedConversion == 0.00 ? "-" : $roundedConversion)  . "</td>";
                            
                            $absentAndLate = $totalAbsent + $roundedConversion;
                            echo "<td class='total_td'>$absentAndLate</td>";

                            $totalVlSl = $row['vl'] + $row['sl'];
                            echo "<td>" . $totalVlSl ."</td>";

                            $remainingVlSl = $totalVlSl - $absentAndLate;
                            $roundedRemainingVlSl = number_format($remainingVlSl, 1);
                            if ($roundedRemainingVlSl > 0) {
                                echo "<td>$roundedRemainingVlSl</td>";
                            }
                            elseif ($roundedRemainingVlSl == 0) {
                                echo "<td>" . abs($roundedRemainingVlSl) . "</td>";
                            }
                            else{
                                echo "<td>(" . abs($roundedRemainingVlSl) . ")</td>";
                            }
                            echo "</tr>";

                            $sql = "UPDATE employees SET remaining_leave = ? WHERE control_number = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ds", $roundedRemainingVlSl, $row['control_number']);
                            $stmt->execute();
                            $stmt->close();
                            

                            /*
                            if ($row['vl'] > 0) {
                                $deductVL = $row['vl'] - $absentAndLate;
                                $sql = "UPDATE employees SET vl = ? WHERE control_number = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ds", $deductVL, $row['control_number']);
                                $stmt->execute();
                                $stmt->close();
                                
                                if ($deductVL < 0) {
                                    $deductSL = $row['sl'] + $deductVL;
                                    $sql = "UPDATE employees SET sl = ? WHERE control_number = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("ds", $deductSL, $row['control_number']);
                                    $stmt->execute();
                                    $stmt->close();
        
                                    $sql = "UPDATE employees SET vl = 0 WHERE control_number = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("s", $row['control_number']);
                                    $stmt->execute();
                                    $stmt->close();
                                }
                            }
                            else {
                                $deductSL = $row['sl'] - $absentAndLate;
                                $sql = "UPDATE employees SET sl = ? WHERE control_number = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ds", $deductSL, $row['control_number']);
                                $stmt->execute();
                                $stmt->close();  
                            }*/

                        }
                        elseif (isset($control_number) && $control_number == $row['control_number']) {
                            echo '<td><button type="submit" id="update" name="updateAttendance">Update</button>
                            <button type="button" id="cancel" name="redirectAttendance">Cancel</button></td>';
                            
                        }
                        else{
                            echo "<td></td>";
                        }
                            
                        
                        $i++;
                    }
                    
                    echo '</tbody>';

                    
                }
                else {
                    echo "<tr><td colspan='24'>0 results</td></tr>";
                    
                }
                
            ?>

            </table>
        </div>
        </form>

        </div>    
    </div>
</body>
<script>
        function resetSearchInput() {
            document.getElementById("name").value = "";
            // Other fields that need to be reset
            
            // Submit the form to show unfiltered data
            document.forms[0].submit();
            <?php
            echo "window.location.href = 'attendance.php';";
            ?>
        } 

        // Add event listener to each row in the table
        document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll("tr[data-row-id]");

    rows.forEach(row => {
        row.addEventListener("dblclick", function () {
            if (this.classList.contains("highlighted-row")) {
                // If the row is already highlighted, remove the highlight from the entire row
                this.classList.remove("highlighted-row");
                // Remove the highlight from all abs_td cells of this row
                const absTdCells = this.querySelectorAll(".abs_td");
                absTdCells.forEach(cell => cell.classList.remove("highlighted-cell"));

                const totalTdCells = this.querySelectorAll(".total_td");
                totalTdCells.forEach(cell => cell.classList.remove("highlighted-cell"));
            } else {
                // Remove the 'highlighted-row' class from all rows
                rows.forEach(row => row.classList.remove("highlighted-row"));
                // Remove the 'highlighted-cell' class from all abs_td cells
                const allAbsTdCells = document.querySelectorAll(".abs_td");
                allAbsTdCells.forEach(cell => cell.classList.remove("highlighted-cell"));

                // Add the 'highlighted-row' class to the entire row
                this.classList.add("highlighted-row");
                // Add the 'highlighted-cell' class to all abs_td cells of this row
                const absTdCells = this.querySelectorAll(".abs_td");
                absTdCells.forEach(cell => cell.classList.add("highlighted-cell"));

                // Remove the 'highlighted-row' class from all rows
                rows.forEach(row => row.classList.remove("highlighted-row"));
                // Remove the 'highlighted-cell' class from all abs_td cells
                const allTotalTdCells = document.querySelectorAll(".total_td");
                allTotalTdCells.forEach(cell => cell.classList.remove("highlighted-cell"));

                // Add the 'highlighted-row' class to the entire row
                this.classList.add("highlighted-row");
                // Add the 'highlighted-cell' class to all abs_td cells of this row
                const totalTdCells = this.querySelectorAll(".total_td");
                totalTdCells.forEach(cell => cell.classList.add("highlighted-cell"));
                
            }
        });
    });
});


        document.getElementById("cancel").addEventListener("click", function() {
            // Redirect to the desired page
            window.location.href = "attendance.php";
        });

        function redirectToAddSchoolYear() {
            <?php
            echo "window.location.href = 'add_school_year.php';";
            ?>
        }


</script>
</html>