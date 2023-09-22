<?php 
session_start();
if (!isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
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
            width: 100%;
            font-size: 14px;
        }
        th, td {
            display: relative;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            white-space: nowrap;
            height: 0px;
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
            z-index: 1000; /* Make sure the fixed row appears above other content */
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
            overflow: auto;
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
        #year{
            width: 157px;
        }
        #specific_date{
            width: 180px;
        }
        #num{
            width: 178px;
        }
        #type{
            width: 260px;
        }
        .search-bar{
            width: 70%;
        }
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
        <form action="" method="POST">
            <table class="search-bar">
            <tr>
            <td colspan="2" rowspan="2"><h1>History</h1></td>
            <td><td></td></td>
            <td>
            <label for="admin_id">Admin ID:</label>
            <input type="text" name="admin_id" id="admin_id" placeholder="Search Admin ID"
            value="<?php echo isset($_POST['admin_id']) ? $_POST['admin_id'] : '' ?>"></input>
            </td>
            <td>
            <label for="employee_id">Employee ID:</label>
            <input type="text" name="employee_id" id="employee_id" placeholder="Search Employee ID"
            value="<?php echo isset($_POST['employee_id']) ? $_POST['employee_id'] : '' ?>"></input>
            </td>
            <td>
            <label for="type">Type:</label>
            <select name="type" id="type">
                <option value="">Select Type</option>
                <option value="Admin Added" <?php if (isset($_POST['type']) && $_POST['type'] === "Admin Added") echo "selected"; ?>>Admin Added</option>
                <option value="Admin Deleted" <?php if (isset($_POST['type']) && $_POST['type'] === "Admin Deleted") echo "selected"; ?>>Admin Deleted</option>
                <option value="Admin Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Admin Updated") echo "selected"; ?>>Admin Updated</option>
                <option value="Admin Profile Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Admin Profile Updated") echo "selected"; ?>>Admin Profile Updated</option>
                <option value="Classification Value Added" <?php if (isset($_POST['type']) && $_POST['type'] === "Classification Value Added") echo "selected"; ?>>Classification Value Added</option>
                <option value="Classification Value Deleted" <?php if (isset($_POST['type']) && $_POST['type'] === "Classification Value Deleted") echo "selected"; ?>>Classification Value Deleted</option>
                <option value="Classification Value Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Classification Value Updated") echo "selected"; ?>>Classification Value Updated</option>
                <option value="Employee Added" <?php if (isset($_POST['type']) && $_POST['type'] === "Employee Added") echo "selected"; ?>>Employee Added</option>
                <option value="Employee Deleted" <?php if (isset($_POST['type']) && $_POST['type'] === "Employee Deleted") echo "selected"; ?>>Employee Deleted</option>
                <option value="Employment Status Value Added" <?php if (isset($_POST['type']) && $_POST['type'] === "Employment Status Value Added") echo "selected"; ?>>Employment Status Value Added</option>
                <option value="Employment Status Value Deleted" <?php if (isset($_POST['type']) && $_POST['type'] === "Employment Status Value Deleted") echo "selected"; ?>>Employment Status Value Deleted</option>
                <option value="Employment Status Value Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Employment Status Value Updated") echo "selected"; ?>>Employment Status Value Updated</option>
                <option value="Image Uploaded" <?php if (isset($_POST['type']) && $_POST['type'] === "Image Uploaded") echo "selected"; ?>>Image Uploaded</option>
                <option value="Information Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Information Updated") echo "selected"; ?>>Information Updated</option>
                <option value="Profile Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Profile Updated") echo "selected"; ?>>Profile Updated</option>
                <option value="Resignation Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Resignation Updated") echo "selected"; ?>>Resignation Updated</option>
                <option value="Service Record Added" <?php if (isset($_POST['type']) && $_POST['type'] === "Service Record Added") echo "selected"; ?>>Service Record Added</option>
                <option value="Service Record Deleted" <?php if (isset($_POST['type']) && $_POST['type'] === "Service Record Deleted") echo "selected"; ?>>Service Record Deleted</option>
                <option value="Service Record Updated" <?php if (isset($_POST['type']) && $_POST['type'] === "Service Record Updated") echo "selected"; ?>>Service Record Updated</option>
                <option value="Username Update" <?php if (isset($_POST['type']) && $_POST['type'] === "Username Update") echo "selected"; ?>>Username Update</option>
            </select>

            </td>
            <td rowspan="2">
                <button type="submit">Search</button>
                <button type="reset" onclick="resetSearchInput()">Reset</button>
            </td>
            </tr>
            <tr>
            <td><td></td></td>
            <td>
            <label for="year">Enter Year: </label>
            <input type="number" id="year" name="year" min="1900" max="2099" step="1" placeholder="Enter Year"
            value="<?php echo isset($_POST['year']) ? $_POST['year'] : '' ?>">
            </td>
            <td>
            <label for="specific_date">Enter Date: </label>
            <input type="date" id="specific_date" name="specific_date"
            value="<?php echo isset($_POST['specific_date']) ? $_POST['specific_date'] : '' ?>">
            </td>
            <td>
                <label for="num">Number to Show: </label>
                <input type="number" id="num" name="num" step="1" value="<?php echo isset($_POST['num']) ? $_POST['num'] : '100' ?>">
            </td>
            </tr>
            </table>
            

        </form>
        
        <div class="container">
            <table>
            <thead>
            <tr class="fixed-row">
            <th>#</th>
            <th>Admin ID</th>
            <th>Admin Name</th>
            <th>Employee ID</th>
            <th>Employee Name</th>
            <th>Type</th>
            <th>Timestamp</th>
            </tr>
            </thead>
            <?php 
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM history";
                // Check if search input is provided
                if (isset($_POST['admin_id']) && !empty($_POST['admin_id'])) {
                    $search_admin_id = $_POST['admin_id'];
                    $sql .= " WHERE admin_id = '$search_admin_id'";
                }
                if (isset($_POST['employee_id']) && !empty($_POST['employee_id'])) {
                    if (isset($_POST['admin_id']) && !empty($_POST['admin_id'])){
                        $sql .= " AND";
                    } else {
                        $sql .= " WHERE";
                    }

                    $search_employee_id = $_POST['employee_id'];
                    $sql .= " employee_id = '$search_employee_id'";
                }
                if (isset($_POST['year']) && !empty($_POST['year'])) {
                    if (isset($_POST['admin_id']) && !empty($_POST['admin_id']) || isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                        $sql .= " AND";
                    } else {
                        $sql .= " WHERE";
                    }
                    $filteredYear = intval($_POST['year']);
                    $sql .= " YEAR(timestamp) = $filteredYear";
                }
                if (isset($_POST['specific_date']) && !empty($_POST['specific_date'])) {
                    if (isset($_POST['admin_id']) && !empty($_POST['admin_id']) || isset($_POST['year']) && !empty($_POST['year']) || isset($_POST['employee_id']) && !empty($_POST['employee_id'])){
                        $sql .= " AND";
                    } else {
                        $sql .= " WHERE";
                    }
                    $filteredDate = $_POST['specific_date'];
                    $sql .= " DATE(timestamp) = '$filteredDate'";
                }
                if (isset($_POST['type']) && !empty($_POST['type'])) {
                    if (isset($_POST['admin_id']) && !empty($_POST['admin_id']) || isset($_POST['year']) && !empty($_POST['year']) || isset($_POST['employee_id']) && !empty($_POST['employee_id']) || isset($_POST['specific_date']) && !empty($_POST['specific_date'])){
                        $sql .= " AND";
                    } else {
                        $sql .= " WHERE";
                    }
                    $filteredType = $_POST['type'];
                    $sql .= " type LIKE '%$filteredType%'";
                }
                
                $sql .= " ORDER BY timestamp DESC LIMIT ";
                if (isset($_POST['num']) && !empty($_POST['num'])){
                    $sql .= $_POST['num'];
                }else{
                    $sql .= 100;
                }
                $result = $conn->query($sql);
                
                $i = 1;
                if ($result->num_rows > 0) {
                        echo '<tbody class="scrollable-content">';
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td> $i </td>";
                        echo "<td>{$row['admin_id']}</td>";
                        echo "<td>{$row['admin_name']}</td>";
                        echo "<td>{$row['employee_id']}</td>";
                        echo "<td>{$row['employee_name']}</td>";
                        echo "<td>{$row['type']}</td>";
                        echo "<td>" . date('M j, Y \a\t g:i a', strtotime($row['timestamp'])) . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                        echo '</tbody>';
                }
                else {
                    echo "<tr><td colspan='5'>0 results</td></tr>";
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
            document.getElementById("admin_id").value = "";
            document.getElementById("employee_id").value = "";
            document.getElementById("year").value = "";
            document.getElementById("specific_date").value = "";
            document.getElementById("type").value = "";
            document.getElementById("num").value = "100";
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

</script>
</html>