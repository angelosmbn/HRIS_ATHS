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

        form {
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
        vertical-align: top;
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
    }

    #recordInputs input[type="text"] {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
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

    #add_record, #back {
        background-color: #007bff;
        margin-bottom: 10px;
    }

    #back {
        background-color: #fc0303;
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
        background-color: #f0f0f0;
        z-index: 0; /* Make sure the fixed row appears above other content */
    }

    form .fixed-row {
        padding: 1rem;
    }

    label {
        font-weight: bold;
    }
    #back {
        background-color: #fc0303;
        float: right;
        clear: right;
    }
</style>
</head>
<body>
    

    <form action="" method="POST">
        <h1>
            <span style="color: black;">School Year (Attendance)</span>  
            <span><button type="button" id="back" onclick="redirectToSettings()">Back</button></span>
        </h1>
        <div>
        <button type="button" id="add_record" onclick="toggleInputs()">Change Record</button>
        </div>
        <div id="recordInputs" style="display: none;">
            <label for="school_year">School Year:</label>
            <input type="text" name="school_year" id="school_year" placeholder="Enter School Year" required><br><br>

            <label for="from_month">From:</label>
            <select name="from_month" id="from_month">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>

            <label for="to_month">To:</label>
            <select name="to_month" id="to_month">
                <option value="January">January</option>
                <option value="February">February</option>
                <option value="March">March</option>
                <option value="April">April</option>
                <option value="May">May</option>
                <option value="June">June</option>
                <option value="July">July</option>
                <option value="August">August</option>
                <option value="September">September</option>
                <option value="October">October</option>
                <option value="November">November</option>
                <option value="December">December</option>
            </select>
            <br><br>
            <button type="submit" name="submitRecord" id="submitRecord">Save</button>
        </div>

        <table>
            <thead>
                <tr class="fixed-row">
                    <th>School Year</th>
                    <th>Months</th>
                </tr>
            </thead>

            <?php
                


                if (isset($_POST['submitRecord'])) {
                    $sql = "SELECT * FROM attendance";
                    $result = mysqli_query($conn, $sql);
                    $resultCheck = mysqli_num_rows($result);

                    if ($resultCheck == 0) {
                        $school_year = $_POST["school_year"];
                        $fromMonth = $_POST["from_month"];
                        $toMonth = $_POST["to_month"];
                        $months = [
                            "January", "February", "March", "April",
                            "May", "June", "July", "August", "September",
                            "October", "November", "December"
                        ];
                    
                        $selectedMonths = [];
                    
                        $fromIndex = array_search($fromMonth, $months);
                        $toIndex = array_search($toMonth, $months);
                    
                        if ($fromIndex !== false && $toIndex !== false) {
                            if ($fromIndex <= $toIndex) {
                                // If the range is within the same year
                                for ($i = $fromIndex; $i <= $toIndex; $i++) {
                                    $selectedMonths[] = $months[$i];
                                }
                            } else {
                                // If the range spans across two years
                                for ($i = $fromIndex; $i < count($months); $i++) {
                                    $selectedMonths[] = $months[$i];
                                }
                                for ($i = 0; $i <= $toIndex; $i++) {
                                    $selectedMonths[] = $months[$i];
                                }
                            }

                            $sql = "SELECT * FROM attendance_year";
                            $result = mysqli_query($conn, $sql);
                            $resultCheck = mysqli_num_rows($result);
                            if ($resultCheck > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $year_id = $row['year_id'];
                                }
                            }

                            $sql = "DELETE FROM attendance_year WHERE year_id = ?";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("i", $year_id);
                            $stmt->execute();

                            $selectedMonthsString = implode(", ", $selectedMonths);
                            
                            $stmt = $conn->prepare("INSERT INTO attendance_year (school_year, months, from_month, to_month) VALUES (?, ?, ?, ?)");
                            $stmt->bind_param("ssss", $school_year, $selectedMonthsString, $fromMonth, $toMonth);
                            $result = $stmt->execute();

                            if ($result) {
                                // Redirect to prevent multiple insertions
                                


                                history($_SESSION['control_number'], "Settings", "School Year Added");
                                echo '<script>window.location="add_school_year.php"</script>';
                                exit();
                            } else {
                                echo "Error: " . $stmt->error; // Use $stmt->error instead of mysqli_error($conn)
                            }

                        } else {
                            echo "Invalid range of months selected";
                        }
                    }
                    else {
                        echo "<script>alert('Please make sure there are no current records of attendance.')</script>";
                        echo '<script>window.location="attendance.php"</script>';
                    }
                }

                $sql = "SELECT * FROM attendance_year";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $year_id = $row['year_id'];
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $row['school_year'] . "</td>";
                        echo "<td>" . $row['months'] . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='3'>No records found.</td></tr>";
                }
            ?>
        </table>
        <br><br>
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

    function redirectToSettings() {
        <?php
            echo "window.location.href = 'attendance.php';";
        ?>
    }

</script>

</html>
