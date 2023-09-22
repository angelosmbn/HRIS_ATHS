<?php 
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['user'])) {
    header("Location: login_hris.php");
    exit();
}
if ($_SESSION['access_level'] == 'employee') {
    echo '<script>
            alert("Invalid Access.");
            window.location="information.php?control=' . $_SESSION['control_number'] . '";
        </script>';
    exit;
}
// Include the navigation bar
include 'navbar_hris.php';
change_default();
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
            border: 1px solid grey;
            border-radius: 5px 0 0 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: 20px auto 0;
            max-height: 750px;
            overflow-y: auto;
            width: 95%;
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
        .container tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table {
            border-collapse: collapse;
            width: auto;
            font-size: 14px;
        }

        th, td {
            display: relative;
            text-align: left;
            padding: 8px;
            border: 1px solid black;
            white-space: nowrap;
            height: auto;
        }

        th{
            background: bisque;
        }

        form td{
            border: none;
        }

        .fixed-row {
            position: sticky;
            top: 0;
            background-color: #f0f0f0;
            z-index: 1000;
        }

        form{
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
            overflow: auto;
        }
        input, select {
            height: 25px;
            margin-left: 10px;
            border-radius: 3px;
            border: 1px solid black;
            padding: 1px;
        }

        .highlighted-row {
            background-color: #5cabff;
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

        #years_service, #years_service1{
            width: 56px;
        }
        #years_service1 {
            margin-left: 0px;
        }

        #civil_status{
            width: 140px;
        }

        #gender{
            width: 115px;
        }
       
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
            <form action="" method="POST">
                <table class="search-bar">
                    <tr>
                        <td colspan="2" rowspan="2"><h1>Current Employees</h1></td>
                        <td><td></td></td>


                        <td>
                            <label for="name">Search Name:</label>
                            <input type="text" name="name" id="name" placeholder="Search Name" 
                            value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>"></input>
                        </td>

                        <td>
                            <label for="gender" id="gender-tab">Gender:</label>
                            <select name="gender" id="gender" >
                                <option value="">Gender</option>
                                <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </td>

                        <td>
                            <label for="civil_status" id="civil-tab">Civil Status:</label>
                            <select name="civil_status" id="civil_status" >
                                <option value="">Civil Status</option>
                                <option value="Single" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Single') ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Married') ? 'selected' : ''; ?>>Married</option>
                                <option value="Widowed" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </td>

                        <td rowspan="2">
                            <button type="submit">Search</button>
                            <button type="reset" onclick="resetSearchInput()">Reset</button>
                            <button type="button" onclick="redirectToAdd()">Add Employee</button>
                        </td>
                    </tr>

                    <tr>
                        <td><td></td></td>

                        <td>
                            <label for="employment_status">Employment Status:</label>
                            <select name="employment_status" id="employment_status" >
                                <option value="">Employment Status</option>
                                <?php 
                                    $data_type = "emp_status";
                                    $sql = "SELECT * FROM data_values WHERE data_type = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("s", $data_type);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="' . $row['data_value'] . '" ' . (isset($_POST['employment_status']) && $_POST['employment_status'] === $row['data_value'] ? 'selected' : '') . '>' . $row['data_value'] . '</option>';
                                    }
                                ?>
                            </select>
                        </td>

                        <td>
                            <label for="years_service">Years:</label>
                            <input type="number" name="years_service" id="years_service" placeholder="From" min="0"
                            value="<?php echo isset($_POST['years_service']) ? $_POST['years_service'] : '' ?>">
                            -
                            <input type="number" name="years_service1" id="years_service1" placeholder="To" min="0"
                            value="<?php echo isset($_POST['years_service1']) ? $_POST['years_service1'] : '' ?>">
                        </td>

                        <td>
                            <label for="position">Position:</label>
                            <input type="text" name="position" id="position" placeholder="Search Position"
                            value="<?php echo isset($_POST['position']) ? $_POST['position'] : '' ?>">
                        </td>
                    </tr>
                </table>
            </form>
        
            <div class="container">
                <table>
                    <thead>
                        <tr class="fixed-row">
                            <th>#</th>
                            <th>Control Number</th>
                            <th>Surname</th>
                            <th>Name</th>
                            <th>Middle Name</th>
                            <th>Suffix</th>
                            <th>Birthday</th>
                            <th>Civil Status</th>
                            <th>Gender</th>
                            <th>Employment Status</th>
                            <th>Classification</th>
                            <th>Date Hired</th>
                            <th>Years in Service</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Email Address</th>
                            <th>Course Taken</th>
                            <th>Further Studies</th>
                            <th>Number of Units</th>
                            <th>PRC number</th>
                            <th>Valid Until</th>
                            <th>Position</th>
                            <th>Tin</th>
                            <th>SSS</th>
                            <th>PHILHEALTH</th>
                            <th>PAG-IBIG</th>
                        </tr>
                    </thead>

                    <?php 
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM employees WHERE status='active'";

                        // Check if search input is provided
                        if (isset($_POST['name']) && !empty($_POST['name'])) {
                            $search_name = $_POST['name'];
                    
                            $sql .= " AND (CONCAT(name, ' ', middle_name, ' ', surname) LIKE '%$search_name%'
                                      OR CONCAT(surname, ' ', name, ' ', middle_name) LIKE '%$search_name%'
                                      OR CONCAT(surname, ' ', name) LIKE '%$search_name%'
                                      OR CONCAT(name, ' ', surname) LIKE '%$search_name%'
                                      OR CONCAT(middle_name, ' ', name) LIKE '%$search_name%'
                                      OR CONCAT(middle_name, ' ', surname) LIKE '%$search_name%'
                                      OR CONCAT(surname, ' ', middle_name) LIKE '%$search_name%')";
                        }

                        if (isset($_POST['gender']) && !empty($_POST['gender'])) {
                            $search_gender = $_POST['gender'];
                        
                            if ($search_gender == 'Male') {
                                $sql .= " AND gender = 'Male'";
                            }
                            else {
                                $sql .= " AND gender = 'Female'";
                            }
                        }

                        if (isset($_POST['civil_status']) && !empty($_POST['civil_status'])) {
                            $search_civil_status = $_POST['civil_status'];

                            $sql .= " AND civil_status LIKE '%$search_civil_status%'";
                        }

                        if (isset($_POST['employment_status']) && !empty($_POST['employment_status'])) {
                            $search_employment_status = $_POST['employment_status'];

                            $sql .= " AND employment_status LIKE '%$search_employment_status%'";
                        }
                        
                        if (isset($_POST['years_service']) && (!empty($_POST['years_service'] || $_POST['years_service'] == 0)) && !(isset($_POST['years_service1']) && !empty($_POST['years_service1']))) {
                            $search_years_service = $_POST['years_service'];

                            if (!empty($_POST['years_service']) || $search_years_service == 0){
                                $sql .= " AND years_in_service = $search_years_service";
                            }
                        }
                        else{
                            if (isset($_POST['years_service']) && !empty($_POST['years_service']) && isset($_POST['years_service1']) && !empty($_POST['years_service1'])) {
                                $search_years_service = $_POST['years_service'];
                                $search_years_service1 = $_POST['years_service1'];

                                if (!empty($_POST['years_service']) || $search_years_service == 0){
                                    $sql .= " AND years_in_service BETWEEN $search_years_service AND $search_years_service1";
                                }
                            }
                        }

                        if (isset($_POST['position']) && !empty($_POST['position'])) {
                            $search_position = $_POST['position'];

                            $sql .= " AND position LIKE '%$search_position%'";
                        }
                        
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            $i = 1;
                                echo '<tbody class="scrollable-content">';

                            while($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr data-row-id='row-$i'>";
                                    $birthday = $row['birthday'];
                                    $formattedBirthday = date('M j, Y', strtotime($birthday));
                                    $date_hired = $row['date_hired'];
                                    $formattedDateHired = date('M j, Y', strtotime($date_hired));
                                    $prc_exp = $row['prc_exp'];
                                    $formattedPrcExp = date('M j, Y', strtotime($prc_exp));
                                    echo "<td> $i </td>";
                                    echo '<td><a href="information.php?control=' . $row['control_number'] . '">' . $row['control_number'] . '</a></td>';
                                    echo "<td>{$row['surname']}</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo "<td>{$row['middle_name']}</td>";
                                    echo "<td>{$row['suffix']}</td>";
                                    echo "<td>$formattedBirthday</td>";//date
                                    echo "<td>{$row['civil_status']}</td>";
                                    echo "<td>{$row['gender']}</td>";
                                    echo "<td>{$row['employment_status']}</td>";
                                    echo "<td>{$row['classification']}</td>";
                                    echo "<td>$formattedDateHired</td>";//date
                                    echo "<td>{$row['years_in_service']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['contact']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['course_taken']}</td>";
                                    echo "<td>{$row['further_studies']}</td>";
                                    echo "<td>{$row['number_of_units']}</td>";
                                    echo "<td>{$row['prc_number']}</td>";
                                    echo "<td>$formattedPrcExp</td>";//date
                                    echo "<td>{$row['position']}</td>";
                                    echo "<td>{$row['tin']}</td>";
                                    echo "<td>{$row['sss']}</td>";
                                    echo "<td>{$row['philhealth']}</td>";
                                    echo "<td>{$row['pag_ibig']}</td>";
                                    echo "</tr>";
                                $i++;
                            }
                                echo '</tbody>';
                        }
                        else {
                            echo "<tr>
                                    <td colspan='24'>0 results</td>
                                 </tr>";
                        }
                        
                    ?>
                </table>
            </div>
        </div>    
    </div>
</body>

<script>
        function resetSearchInput() {
            // Reset the search input field
            document.getElementById("name").value = "";
            document.getElementById("gender").value = "";
            document.getElementById("civil_status").value = "";
            document.getElementById("employment_status").value = "";
            document.getElementById("years_service").value = "";
            document.getElementById("years_service1").value = "";
            document.getElementById("position").value = "";
            // Submit the form to show unfiltered data
            document.forms[0].submit();
        }

        // Add event listener to each row in the table
        document.addEventListener("DOMContentLoaded", function () {
            const rows = document.querySelectorAll("tr[data-row-id]");

            rows.forEach((row) => {
                row.addEventListener("dblclick", function () {
                    if (this.classList.contains("highlighted-row")) {
                        // If the row is already highlighted, remove the highlight
                        this.classList.remove("highlighted-row");
                        this.style.backgroundColor = "";
                    } else {
                        // Remove the 'highlighted-row' class from all rows
                        rows.forEach((r) => {
                            r.classList.remove("highlighted-row");
                            r.style.backgroundColor = "";
                        });

                        // Add the 'highlighted-row' class to the clicked row
                        this.classList.add("highlighted-row");
                        // Change background color to blue for double-clicked row
                        this.style.backgroundColor = "#5cabff";
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            // Get references to the input fields
            const yearsServiceInput = document.getElementById("years_service");
            const yearsService1Input = document.getElementById("years_service1");

            // Add an event listener to the yearsServiceInput
            yearsServiceInput.addEventListener("input", function () {
                // Get the value of yearsServiceInput
                const yearsServiceValue = parseInt(yearsServiceInput.value);

                // Update the min attribute of yearsService1Input
                yearsService1Input.min = yearsServiceValue;
                
                // If the value of yearsService1Input is less than the new min, update its value
                if (parseInt(yearsService1Input.value) < yearsServiceValue) {
                    yearsService1Input.value = yearsServiceValue;
                }
            });
        });

        function redirectToAdd() {
        <?php
        echo "window.location.href = 'add_emp.php';";
        ?>
    }
</script>

</html>