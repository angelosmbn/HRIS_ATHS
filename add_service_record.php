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
    }else{
        echo '<script>
                alert("Please Select an Employee.");
                window.location="cur_emp.php"
            </script>';
    }

    if (!empty($control_number)) {
        $emp_status = '';
        $name = '';

        $stmt = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row['surname'] . ', ' . $row['name'];
            if ($row['middle_name'] != "") {
                $name .= ' ' . $row['middle_name'];
            }
            if ($row['suffix'] != "") {
                $name .= ' ' . $row['suffix'];
            } 
            $emp_status = $row['status'];
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

    form {
        background-color: #fff;
        display: relative;
        padding: 0rem 1rem 1rem 1rem;
        margin-right: 20px
        border-radius: 5px 0px 0px 0px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin-left: 100px;
        border: 1px solid black;
        height: 820px;
        overflow-y: auto;
        width: 60%;
        margin-left: 420px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        display: relative;
        text-align: left;
        padding: 8px;
        border: 1px solid black;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
        font-size: 15px;
    }

    th {
        background: bisque;
    }

    #recordInputs {
        display: none;
    }

    #information {
        width: 100%;
        resize: vertical;
        min-height: 100px;
        margin-top: 10px;
        font-size: 15px;
    }

    #recordInputs input[type="text"] {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 15px;
    }

    #recordInputs button, #add_record, #back {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    #add_record {
        background-color: #007bff;
        margin-bottom: 10px;
    }

    #back {
        background-color: #fc0303;
        float: right;
        clear: right;
    }

    #back:hover {
        background-color: #9c0402;
    }

    #add_record:hover {
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
        top: 0;
        background-color: white;
        z-index: 0; /* Make sure the fixed row appears above other content */
        padding: 1rem;
        margin-left: -12px;
        padding-bottom: 0px;
    }
    .fixed-row1 {
        position: sticky;
        top: 60px;
        background-color: #f0f0f0;
        z-index: 0; /* Make sure the fixed row appears above other content */
        padding: 1rem;
    }
</style>
</head>
<body>

    

    <form action="" method="POST">
        <h1 class="fixed-row">
            <span style="color: black;">Service Record</span>  
            (<span style="color: <?php echo ($emp_status == 'resigned') ? 'red' : 'green'; ?>">
                <?php echo $name; ?>
            </span>)
            <span><button type="button" id="back" onclick="redirectToServiceRecord()">Back</button></span>
        </h1>
        <?php if ($_SESSION['access_level'] != 'employee'){ ?>
        <button type="button" id="add_record" onclick="toggleInputs()">Add Record</button>
        
        <div id="recordInputs" style="display: none;">
            <input type="text" name="schoolYear" placeholder="School Year" required><br>
            <input type="text" name="status" placeholder="Status"><br>
            <textarea name="information" id="information" placeholder="Load / Teaching / Non-Teaching" rows="4"></textarea><br>
            <button type="submit" name="submitRecord" id="submitRecord">Save</button>
        </div>
        <?php } ?>
        <table>
            <thead>
                <tr class="fixed-row1">
                    <th>School Year</th>
                    <th>Status</th>
                    <th>LOAD / TEACHING / NON TEACHING</th>
                </tr>
            </thead>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
                    $schoolYear = $_POST['schoolYear'];
                    $status = $_POST['status'];
                    $information = $_POST['information'];
                    $sql = "INSERT INTO service_record (control_number, school_year, status, information) VALUES ('$control_number', '$schoolYear', '$status', '$information')";
                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        // Redirect to prevent multiple insertions
                        $type = "Service Record Added";
                        history($_SESSION['control_number'], $control_number, $type);
                        echo '<script>window.location="add_service_record.php?control=' . $control_number . '"</script>';
                        exit();
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }

                $sql = "SELECT * FROM service_record WHERE control_number = '$control_number' ORDER BY school_year ASC";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $string = explode(', ', $row['information']);
                        $formatted_string = implode("<br>", $string);
                        echo "<tr data-row-id='row-$i'>";
                        if ($_SESSION['access_level'] != 'employee'){
                            echo "<td><a href='edit_service_record.php?service_id=" . $row['service_id'] . "'>" . $row['school_year'] . "</a></td>";
                        }else{
                            echo "<td>" . $row['school_year'] . "</td>";
                        }
                       
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $formatted_string . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='3'>No records found.</td></tr>";
                }
            ?>
        </table>
    </form>
</body>

<script>
    function toggleInputs() {
        var inputs = document.getElementById("recordInputs");
        inputs.style.display = (inputs.style.display === "none") ? "block" : "none";
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

    function redirectToServiceRecord() {
        <?php
            if (isset($control_number)) {
                echo "window.location.href = 'information.php?control=$control_number';";
            } else {
                echo "window.location.href = 'cur_emp.php';";
            }
        ?>
    }
</script>

</html>
