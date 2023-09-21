<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }

    include 'navbar_hris.php';
    change_default();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
        // Check if 'monthSelect' is set in the POST data
        if (isset($_POST['monthSelect'])) {
            $month = $_POST['monthSelect'];
            $monthNames = [
                '01' => 'January',
                '02' => 'February',
                '03' => 'March',
                '04' => 'April',
                '05' => 'May',
                '06' => 'June',
                '07' => 'July',
                '08' => 'August',
                '09' => 'September',
                '10' => 'October',
                '11' => 'November',
                '12' => 'December'
            ];
    
            // Set the $currentMonthWord based on the selected month
            $currentMonthWord = $monthNames[$month];
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
        max-height: 700px;
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
        border: 1px solid black;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
    }

    th {
        background: bisque;
    }

    #information {
        width: 100%;
        resize: vertical;
        min-height: 100px;
        margin-top: 10px;
    }

    select {
        width: 40%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    #back {
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

    button:hover {
        background-color: #0056b3;
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
        top: -15px;
        background-color: #f0f0f0;
        z-index: 0; /* Make sure the fixed row appears above other content */
    }

    form .fixed-row {
        padding: 1rem;
    }

    label {
        font-weight: bold;
    }
    
</style>
</head>
<body>
    

    <div class="container">
        <h1>
            <span style="color: black;">Birthday Celebrants</span>  
            <span><button type="button" id="back" onclick="redirectToSettings()">Back</button></span>
        </h1>
        <div>
            <form action="" method="POST" class="search_bar">
            <label for="monthSelect">Select a Month:</label>
            <select id="monthSelect" name="monthSelect" required>
                <option value="">Select a month</option>
                <option value="01" <?php if (isset($month) && $month === '01') echo 'selected'; ?>>January</option>
                <option value="02" <?php if (isset($month) && $month === '02') echo 'selected'; ?>>February</option>
                <option value="03" <?php if (isset($month) && $month === '03') echo 'selected'; ?>>March</option>
                <option value="04" <?php if (isset($month) && $month === '04') echo 'selected'; ?>>April</option>
                <option value="05" <?php if (isset($month) && $month === '05') echo 'selected'; ?>>May</option>
                <option value="06" <?php if (isset($month) && $month === '06') echo 'selected'; ?>>June</option>
                <option value="07" <?php if (isset($month) && $month === '07') echo 'selected'; ?>>July</option>
                <option value="08" <?php if (isset($month) && $month === '08') echo 'selected'; ?>>August</option>
                <option value="09" <?php if (isset($month) && $month === '09') echo 'selected'; ?>>September</option>
                <option value="10" <?php if (isset($month) && $month === '10') echo 'selected'; ?>>October</option>
                <option value="11" <?php if (isset($month) && $month === '11') echo 'selected'; ?>>November</option>
                <option value="12" <?php if (isset($month) && $month === '12') echo 'selected'; ?>>December</option>
            </select>



                <button type="submit" name="search" id="search">Search</button>
                <button type="reset" id="reset" onclick="resetSearchInput()">Reset</button>
            </form>
        </div>
        <table>
            <thead>
                <?php 
                if (isset($month) && $month != ""){
                    $currentMonth = $month;
                } else {
                    $currentMonth = date('m');
                    $currentMonthWord = date('F');
                }
                ?>
                <tr>
                    <th colspan="4" style="text-align: center;"><?php echo $currentMonthWord ?></th>
                </tr>
                <tr class="fixed-row">
                    <th>Name</th>
                    <th>Age</th>
                    <th>Day</th>
                    <th>Birthday</th>
                </tr>
            </thead>

            <?php
                $currentYear = date('Y'); // Get the current year
                $sql = "SELECT * FROM employees WHERE MONTH(birthday) = $currentMonth ORDER BY DAY(birthday) ASC";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        $fullName = $row['surname'] . ", " . $row['name'];
                        if ($row['middle_name'] != "") {
                            $fullName .= " " . $row['middle_name'];
                        }
                        if ($row['suffix'] != "") {
                            $fullName .= " " . $row['suffix'];
                        }
                        
                        // Calculate the day of the week for the birthday on the current year
                        $birthdayThisYear = $currentYear . substr($row['birthday'], 4); // Replace year with current year
                        $birthdayTimestamp = strtotime($birthdayThisYear);
                        $day = date('l', $birthdayTimestamp);
                
                        $birthday = date('F j, Y', strtotime($row['birthday']));
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $fullName . "</td>";
                        echo "<td>" . $row['age'] . "</td>";
                        echo "<td>" . $day . "</td>"; // Store the day in the $day variable
                        echo "<td>" . $birthday . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='4'>No celebrants found for this month.</td></tr>";
                }

                
            ?>

        </table>
    </div>
</body>

<script>
    function resetSearchInput() {
            // Reset the search input field
            document.getElementById("monthSelect").value = "";
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
            echo "window.location.href = 'cur_emp.php';";
        ?>
    }
</script>

</html>
