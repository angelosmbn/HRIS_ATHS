<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    if ($_SESSION['access_level'] != 'super admin') {
        echo '<script>
                alert("Invalid Access!");
                window.location="Admin_home_hris.php"
            </script>';
        exit;
    }

    include 'navbar_hris.php';
    change_default();
        // Function to get existing access_level for a control_number
        function getExistingAccessLevel($conn, $control_number) {
            $sql = "SELECT access_level FROM user WHERE control_number = '$control_number'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            return $row['access_level'];
        }
        
        // Function to get existing status for a control_number
        function getExistingStatus($conn, $control_number) {
            $sql = "SELECT status FROM user WHERE control_number = '$control_number'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            return $row['status'];
        }

    if (isset($_POST['submitRecord'])) {
        // Loop through the POST data to find the updated values.
        $flag = false;
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'access_level_') === 0) {
                // Extract the control_number from the key.
                $control_number = substr($key, strlen('access_level_'));
        
                // Sanitize the input and prevent SQL injection.
                $access_level = mysqli_real_escape_string($conn, $value);
        
                // Check if there is a change in access_level
                $existing_access_level = getExistingAccessLevel($conn, $control_number);
        
                if ($existing_access_level !== $access_level) {
                    // Update the database with the new access_level.
                    $updateSql = "UPDATE user SET access_level = '$access_level' WHERE control_number = '$control_number'";
                    mysqli_query($conn, $updateSql);
        
                    history($_SESSION['control_number'], $control_number, "Updated Access Level");
                    $flag = true;
                }
            } elseif (strpos($key, 'status_') === 0) {
                // Extract the control_number from the key.
                $control_number = substr($key, strlen('status_'));
        
                // Sanitize the input and prevent SQL injection.
                $status = mysqli_real_escape_string($conn, $value);
        
                // Check if there is a change in status
                $existing_status = getExistingStatus($conn, $control_number);
        
                if ($existing_status !== $status) {
                    // Update the database with the new status.
                    $updateSql = "UPDATE user SET status = '$status' WHERE control_number = '$control_number'";
                    mysqli_query($conn, $updateSql);
        
                    history($_SESSION['control_number'], $control_number, "Updated Status");
                    $flag = true;
                }
            }
        }

        $flag2 = false;
        if (isset($_POST['selected_users'])) {
            $selected_users = $_POST['selected_users'];
            
            foreach ($selected_users as $control_number) {
                $get_birthday = "SELECT birthday FROM employees WHERE control_number = '$control_number'";
                $result = mysqli_query($conn, $get_birthday);
                $row = mysqli_fetch_assoc($result);
                $birthday = $row['birthday'];
                
                $encrypted_username = sha1($control_number);
                $encrypted_password = sha1($birthday);
                $update_query = "UPDATE user SET username = ?, password = ? WHERE control_number = ?";
                $stmt_update = $conn->prepare($update_query);
                $stmt_update->bind_param("sss", $encrypted_username, $encrypted_password, $control_number);
                $stmt_update->execute();
                $stmt_update->close();
    
                history($_SESSION['control_number'], $control_number, "Reset Admin Username & Password");
                $flag2 = true;
            }
        }

        // Redirect back to the previous page or wherever you want to go after updating.
        if ($flag && $flag2) {
            echo '<script>
            alert("Successfully Reset & Updated Admin!");
            window.location="manage_admin.php?"
        </script>';
        }
        elseif ($flag) {
            echo '<script>
            alert("Successfully Updated Admin!");
            window.location="manage_admin.php?"
        </script>';
        }
        elseif ($flag2) {
            echo '<script>
        alert("Successfully Reset Admin Username & Password! - ' . $birthday . '");
        window.location="manage_admin.php";
    </script>';

        }
        else{
            echo '<script>
            alert("There Were No Changes.");
            window.location="manage_admin.php"
        </script>';
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

    .search_bar {
        display: flex;
    }

    .container {
        display: flex;
        flex-direction: column;
        height: 100vh;
        background-color: #fff;
        padding: 1rem;
        width: 50%;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin: auto; /* Center horizontally */
        border: 0.5px solid black;
        max-height: 600px;
        overflow: auto;
    }


    .records table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        border: 1px solid black;
    }

    .records th, .records td, .records tr {
        display: relative;
        text-align: left;
        padding: 8px;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
        border: 1px solid black;
    }

    label {
        font-weight: bold;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 16px;
    }

    th, td {
        display: relative;
        text-align: left;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
    }

    th {
        background: bisque;
    }

    input, select{
        min-width: 50%;
        border: 1px solid black;
        border-radius: 3px;
        margin-left: 15px;
        margin-right: 15px;
    }

    #search, #reset {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 4px 4px;
        border-radius: 3px;
        cursor: pointer;
    }

    #reset {
        margin-left: 10px;
    }

    #recordInputs {
        display: none;
    }
 

    #recordInputs button, #add_record, #back, #submitRecord {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    #add_record, #back {
        background-color: #007bff;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    #back {
        background-color: #fc0303;
    }

    #back:hover {
        background-color: #9c0402;
    }

    #search:hover {
        background-color: #0056b3;
    }

    #submitRecord:hover {
        background-color: #3a8a3d;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    a:hover {
        color: #0056b3;
        font-weight: bold;
    }

    .highlighted-row {
        background-color: #5cabff;
    }

    td #click:hover {
        cursor: pointer;
    }

    .fixed-row {
        position: sticky;
        top: -20px;
        background-color: #f0f0f0;
        z-index: 0; /* Make sure the fixed row appears above other content */
    }

    .container .fixed-row {
        padding: 1rem;
    }

    form {

    }
</style>
</head>
<body>
    

    <div class="container">
        <h1>
            <span style="color: black;">Manage Admin</span>  
        </h1>
        <div>
            <form action="" method="POST" class="search_bar">
                <label for="name">Search Name:</label>
                <input type="text" name="name" id="name" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ""; ?>">
                <button type="submit" name="search" id="search">Search</button>
                <button type="reset" id="reset" onclick="resetSearchInput()">Reset</button>
            </form>
        </div>
        <form action="" method="POST">
        <div>
            <button type="submit" name="submitRecord" id="submitRecord">Save</button>
            <button type="button" id="back" onclick="redirectToSettings()">Back</button>
        </div>

        <table class="records">
            <thead>
                <tr class="fixed-row">
                    <th>#</th>
                    <th>Control Number</th>
                    <th>Name</th>
                    <th>Access Level</th>
                    <th>Status</th>
                    <th>Reset</th>
                </tr>
            </thead>

            <?php
                $sql = "SELECT * FROM user WHERE access_level != 'employee'";
               

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

                $sql .= " ORDER BY access_level ASC, status ASC, surname ASC, name ASC, middle_name ASC";

                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fname = $row['surname'] . ", " . $row['name'];
                        if (!empty($row['middle_name'])) {
                            $fname .= " " . $row['middle_name'];
                        }
                        if ($row['suffix'] != "") {
                            $fname .= " " . $row['suffix'];
                        }
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . $row['control_number'] . "</a></td>";
                        echo "<td>" . $fname . "</td>"; 
                        echo '<td><select name="access_level_' . $row['control_number'] . '" id="access_level">';
                        echo '<option value="super admin" ' . ($row['access_level'] == 'super_admin' ? 'selected' : '') . '>Super Admin</option>';
                        echo '<option value="admin" ' . ($row['access_level'] == 'admin' ? 'selected' : '') . '>Admin</option>';
                        echo '<option value="employee" ' . ($row['access_level'] == 'employee' ? 'selected' : '') . '>Employee</option>';
                        echo '</select></td>';
                        echo '<td><select name="status_' . $row['control_number'] . '" id="status">';
                        echo '<option value="active" ' . ($row['status'] == 'active' ? 'selected' : '') . '>Active</option>';
                        echo '<option value="disabled" ' . ($row['status'] == 'disabled' ? 'selected' : '') . '>Disabled</option>';
                        echo '</select></td>';
                        echo '<td><input type="checkbox" name="selected_users[]" value="' . $row['control_number'] . '"></td>';
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='5'>No records found.</td></tr>";
                }
            ?>
        </table>
    </div>
    </form>
</body>

<script>

    function resetSearchInput() {
            // Reset the search input field
            document.getElementById("name").value = "";
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

    function redirectToSettings() {
        <?php
            echo "window.location.href = 'settings.php';";
        ?>
    }

</script>

</html>
