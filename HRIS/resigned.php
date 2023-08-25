<?php 
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
    header("Location: login_hris.php");
    exit();
}
include 'navbar_hris.php';
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

        form td{
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
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
        <form action="" method="POST">
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
            <label for="year">Resignation Year: </label>
            <input type="text" id="year" name="year" placeholder="Search Year"
            value="<?php echo isset($_POST['year']) ? $_POST['year'] : '' ?>">
            </td>
            </tr>
            </table>
            

            
        </form>
        
        <div class="container">
            <table>
            <thead>
            <tr class="fixed-row">
            <th>#</th>
            <th>Control Number</th><th>Resignation Date</th><th>Surname</th><th>Name</th><th>Middle Name</th>
            <th>Birthday</th><th>Civil Status</th><th>Gender</th><th>Employment Status</th>
            <th>Classification</th><th>Date Hired</th><th>Years in Service</th><th>Address</th>
            <th>Contact Number</th><th>Email Address</th><th>Course Taken</th><th>Further Studies</th>
            <th>Number of Units</th><th>PRC number</th><th>Valid Until</th><th>Position</th>
            <th>Tin</th><th>SSS</th><th>PHILHEALTH</th><th>PAG-IBIG</th>
            </tr>
            </thead>
            <?php 
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM employees WHERE status = 'resigned'";
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
                    $sql .= " AND YEAR(resignation_date) = $filteredYear";
                }
                
            
                $result = $conn->query($sql);
                $i = 1;
                if ($result->num_rows > 0) {
                        echo '<tbody class="scrollable-content">';
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td> $i </td>";
                        echo '<td><a href="information.php?control=' . $row['control_number'] . '">' . $row['control_number'] . '</a></td>';
                        echo "<td>{$row['resignation_date']}</td>";
                        echo "<td>{$row['surname']}</td>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['middle_name']}</td>";
                        echo "<td>{$row['birthday']}</td>";
                        echo "<td>{$row['civil_status']}</td>";
                        echo "<td>{$row['gender']}</td>";
                        echo "<td>{$row['employment_status']}</td>";
                        echo "<td>{$row['classification']}</td>";
                        echo "<td>{$row['date_hired']}</td>";
                        echo "<td>{$row['years_in_service']}</td>";
                        echo "<td>{$row['address']}</td>";
                        echo "<td>{$row['contact']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['course_taken']}</td>";
                        echo "<td>{$row['further_studies']}</td>";
                        echo "<td>{$row['number_of_units']}</td>";
                        echo "<td>{$row['prc_number']}</td>";
                        echo "<td>{$row['prc_exp']}</td>";
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
                    echo "<tr><td colspan='24'>0 results</td></tr>";
                    
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

</script>
</html>