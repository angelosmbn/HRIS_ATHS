<?php 
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
    header("Location: login_hris.php");
    exit();
}
include 'navbar_hris.php';
change_default();
function getCheckedValues() {
    $checkedValues = array();

    if (isset($_POST['emp']) && is_array($_POST['emp'])) {
        foreach ($_POST['emp'] as $value) {
            // You may want to sanitize or validate the value here if needed
            $checkedValues[] = $value;
        }
    }

    return $checkedValues;
}

if (isset($_POST['submit']) && isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
    $checkedValues = getCheckedValues();

    // Check if $checkedValues is not empty before proceeding with the update
    if (!empty($checkedValues)) {
        $sql = "UPDATE employees SET years_in_service = years_in_service + 1 WHERE control_number = ?";
        $stmt = $conn->prepare($sql);

        foreach ($checkedValues as $value) {
            $stmt->bind_param("s", $value); // Assuming control_number is a string
            $stmt->execute();
        }

        $stmt->close();

        history($_SESSION['control_number'], "Increment Years", "Incremented Years in Service");
        echo '<script>
            alert("Successfully Updated Employee Years in Service.");
            window.location="increment_years.php";
            </script>';
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
            max-height: 700px;
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
            text-align: left;
            padding: 8px;
            border: 1px solid #ccc;
            white-space: nowrap;
            height: auto;
        }

        th{
            background: bisque;
        }

        .search td{
            border: none;
        }

        .fixed-row {
            position: sticky;
            top: 0;
            background-color: #f0f0f0;
            z-index: 1000;
        }

        .scrollable-content {
            /* Add appropriate padding to the top to prevent content from being hidden behind the fixed row */
            padding-top: /* height of the fixed row */;
        }
        .search{
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
        #year{
            width: 178px;
        }
        .search-bar{
            width: 1000px;
        }
        #name_td{
            width: 247px;
        }
        #input_td {
            padding: 3px;
            text-align: center;
        }
        #yis_td {
            text-align: center;
        }
        input[type="checkbox"] {
            width: 15px;
            height: 15px;
        }
        #submit_td {
            padding: 0%;
        }
        #name_th {
            min-width: 246px;
        }
        #date_th {
            min-width: 109px;
        }
        #check_th {
            min-width: 28px;
        }
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
        <form action="" method="POST" class="search">
            <table class="search-bar">
            <tr>
            <td colspan="2" rowspan="2"><h1>Resigned Employees</h1></td>
            <td><td></td></td>
            <td><td></td></td>
            <td><td></td></td>
            <td>
            <label for="name">Search Name:</label>
            <input type="text" name="name" id="name" placeholder="Search Name"
            value="<?php echo isset($_POST['name']) ? $_POST['name'] : '' ?>"></input>
            </td>
            <td rowspan="2"><button type="submit">Search</button>
            <button type="reset" onclick="resetSearchInput()">Reset</button>
            </td>
            </tr>
            <tr>
            <td><td></td></td>
            <td><td></td></td>
            <td><td></td></td>
            <td>
            <label for="year">Hiring Year: </label>
            <input type="text" id="year" name="year" placeholder="Search Year"
            value="<?php echo isset($_POST['year']) ? $_POST['year'] : '' ?>">
            </td>
            </tr>
            </table>
            

            
        </form>
        
        <form action="" method="POST" onsubmit="return checkIfAnyChecked()">
            <input type="hidden" name="confirm" value="">
            <div class="container">
                <table>
                <thead>
                <tr class="fixed-row">
                <th><input type="checkbox" name="all" id="all"></th>
                <th id="name_th">Employee Name</th>
                <th id="date_th">Date Hired</th>
                <th id="yis_th">YIS</th>
                <th></th>
                <th id="check_th">✓</th>
                <th id="name_th">Employee Name</th>
                <th id="date_th">Date Hired</th>
                <th id="yis_th">YIS</th>
                <th></th>
                <th id="check_th">✓</th>
                <th id="name_th">Employee Name</th>
                <th id="date_th">Date Hired</th>
                <th id="yis_th">YIS</th>
                <th></th>
                <th id="check_th">✓</th>
                <th id="name_th">Employee Name</th>
                <th id="date_th">Date Hired</th>
                <th id="yis_th">YIS</th>
                </tr>
                </thead>
                <?php 
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM employees WHERE status = 'active'";
                    // Check if search input is provided
                    if (isset($_POST['name']) && !empty($_POST['name'])) {
                        $search_name = $_POST['name'];
                        // Add all possible combinations of name search to the SQL query
                        $sql .= " AND (CONCAT(name, ' ', middle_name, ' ', surname) LIKE '%$search_name%'
                        OR CONCAT(surname, ' ', name, ' ', middle_name) LIKE '%$search_name%'
                        OR CONCAT(surname, ' ', name) LIKE '%$search_name%'
                        OR CONCAT(name, ' ', surname) LIKE '%$search_name%'
                        OR CONCAT(middle_name, ' ', name) LIKE '%$search_name%'
                        OR CONCAT(middle_name, ' ', surname) LIKE '%$search_name%'
                        OR CONCAT(surname, ' ', middle_name) LIKE '%$search_name%')";
                    }
                    if (isset($_POST['year']) && !empty($_POST['year'])) {
                        $filteredYear = intval($_POST['year']);
                        $sql .= " AND YEAR(date_hired) = $filteredYear";
                    }
                    
                
                    $result = $conn->query($sql);
                    $i = 0;
                    
                    if ($result->num_rows > 0) {
                        echo '<tbody class="scrollable-content">';
                        $i = 0;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $fname = $row['surname'] . ', ' . $row['name'];
                            $date_hired = date("M d, Y", strtotime($row["date_hired"]));
                        
                            if ($row['middle_name'] !== "") {
                                $fname .= " " . $row['middle_name'][0] . ".";
                            }
                        
                            if ($i % 4 == 0) {
                                echo "<tr data-row-id='row-$i'>";
                            }
                        
                            echo "<td id='input_td'><input type='checkbox' name='emp[]' value='{$row['control_number']}'></td>";
                            echo "<td id='name_td'>{$fname}</td>";
                            echo '<td>' . $date_hired . '</td>';
                            echo '<td>' . $row["years_in_service"] . '</td>';
                        
                            if ($i % 4 == 0 || $i % 4 == 1 || $i % 4 == 2) {
                                echo "<td style='background-color: bisque;'></td>";
                            }
                        
                            if ($i % 4 == 3 || $i == $result->num_rows - 1) {
                                echo "</tr>";
                            }
                        
                            $i++;
                        }
                        echo '<tr><td colspan="20" id="submit_td"><button type="submit" name="submit" onclick="confirmUpdate()">Submit</button></td>';
                        echo '</tbody>';
                    } else {
                        echo "<tr><td colspan='20'>0 results</td></tr>";
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
            // Reset the search input field
            document.getElementById("name").value = "";
            document.getElementById("year").value = "";
            // Submit the form to show unfiltered data
            document.forms[0].submit();
        }

        // Add event listener to each row in the table
        document.addEventListener("DOMContentLoaded", function () {
            const rows = document.querySelectorAll("tr[data-row-id]");

            rows.forEach(row => {
                row.addEventListener("dblclick", function () {
                    if (this.classList.contains("highlighted-row")) {
                        // If the row is already highlighted, remove the highlight
                        this.classList.remove("highlighted-row");
                    } else {
                        // Remove the 'highlighted-row' class from all rows
                        rows.forEach(row => row.classList.remove("highlighted-row"));

                        // Add the 'highlighted-row' class to the clicked row
                        this.classList.add("highlighted-row");
                    }
                });
            });
        });

        const allCheckbox = document.getElementById('all');
        const empCheckboxes = document.querySelectorAll('input[name="emp[]"]');

        allCheckbox.addEventListener('change', function () {
            empCheckboxes.forEach((checkbox) => {
                checkbox.checked = allCheckbox.checked;
            });
        });

        function confirmUpdate() {
            const checkboxes = document.querySelectorAll('input[name="emp[]"]');
            let anyChecked = false;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    anyChecked = true;
                }
            });

            if (anyChecked) {
                const userConfirmed = confirm('Are you sure you want to increment years in service for selected employees?');
                if (userConfirmed) {
                    // Set the value of the hidden input field to 'yes' if the user confirms
                    document.querySelector('input[name="confirm"]').value = 'yes';
                    document.forms[0].submit(); // Submit the form if confirmed
                } else {
                    // Set the value of the hidden input field to 'no' if the user cancels
                    document.querySelector('input[name="confirm"]').value = 'no';
                }
            }
        }

        function checkIfAnyChecked() {
            const checkboxes = document.querySelectorAll('input[name="emp[]"]');
            let anyChecked = false;

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    anyChecked = true;
                }
            });

            if (!anyChecked) {
                alert("There Are No Selected Employees.");
                window.location = "increment_years.php";
                return false; // Prevent form submission
            }

            // If at least one checkbox is checked, proceed with the form submission
            return true;
        }
    </script>
</html>