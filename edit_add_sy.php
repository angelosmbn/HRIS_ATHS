<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    
    include 'navbar_hris.php';
    change_default();
    
    if (isset($_GET['year_id'])) {
        $year_id = $_GET['year_id'];   
    }

    $sql = "SELECT * FROM attendance_year WHERE year_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $year_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $school_year = $row['school_year'];
        $months = $row['months'];
        $fromMonth = $row['from_month'];
        $toMonth = $row['to_month'];
    }
    else {
        echo "No data found.";
    }

    if (isset($_POST['deleteRecord'])) {
        $sql = "DELETE FROM attendance_year WHERE year_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $year_id);
        $stmt->execute();
        $stmt->close();

        history($_SESSION['control_number'], "Settings", "Attendance Year Deleted");
        echo '<script>window.location="add_school_year.php?"</script>';
        
        exit();
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

    #information {
        width: 100%;
        resize: vertical;
        min-height: 100px;
        margin-top: 10px;
    }
    
    input[type="text"] {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    
    button[type="submit"], #add_record, #cancel {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 20px;
        width: 100px;
    }

    #cancel {
        background-color: #007bff;
    }

    #cancel:hover {
        background-color: #0056b3;
    }

    button[type="submit"]:hover {
        background-color: #3a8a3d;
    }

    .right-label {
        float: right;
        clear: right;
    }

    button[name="deleteRecord"] {
        background-color: #f44336;
    }

    button[name="deleteRecord"]:hover {
        background-color: #9c0402;
    }
    label{
        font-weight: bold;
    }
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Edit Employment Status Value</h1><br>

        <label for="school_year">School Year:</label>
        <input type="text" name="school_year" id="school_year" placeholder="Enter Value" value="<?php echo isset($school_year) ? $school_year : '' ?>"><br>
        
        <div>
            <label for="from_month">From:</label>
            <select name="from_month" id="from_month">
                <option value="January" <?php echo (isset($fromMonth) && $fromMonth === 'January') ? 'selected' : ''; ?>>January</option>
                <option value="February" <?php echo (isset($fromMonth) && $fromMonth === 'February') ? 'selected' : ''; ?>>February</option>
                <option value="March" <?php echo (isset($fromMonth) && $fromMonth === 'March') ? 'selected' : ''; ?>>March</option>
                <option value="April" <?php echo (isset($fromMonth) && $fromMonth === 'April') ? 'selected' : ''; ?>>April</option>
                <option value="May" <?php echo (isset($fromMonth) && $fromMonth === 'May') ? 'selected' : ''; ?>>May</option>
                <option value="June" <?php echo (isset($fromMonth) && $fromMonth === 'June') ? 'selected' : ''; ?>>June</option>
                <option value="July" <?php echo (isset($fromMonth) && $fromMonth === 'July') ? 'selected' : ''; ?>>July</option>
                <option value="August" <?php echo (isset($fromMonth) && $fromMonth === 'August') ? 'selected' : ''; ?>>August</option>
                <option value="September" <?php echo (isset($fromMonth) && $fromMonth === 'September') ? 'selected' : ''; ?>>September</option>
                <option value="October" <?php echo (isset($fromMonth) && $fromMonth === 'October') ? 'selected' : ''; ?>>October</option>
                <option value="November" <?php echo (isset($fromMonth) && $fromMonth === 'November') ? 'selected' : ''; ?>>November</option>
                <option value="December" <?php echo (isset($fromMonth) && $fromMonth === 'December') ? 'selected' : ''; ?>>December</option>
            </select>

            <label for="to_month">To:</label>
            <select name="to_month" id="to_month">
                <option value="January" <?php echo (isset($toMonth) && $toMonth === 'January') ? 'selected' : ''; ?>>January</option>
                <option value="February" <?php echo (isset($toMonth) && $toMonth === 'February') ? 'selected' : ''; ?>>February</option>
                <option value="March" <?php echo (isset($toMonth) && $toMonth === 'March') ? 'selected' : ''; ?>>March</option>
                <option value="April" <?php echo (isset($toMonth) && $toMonth === 'April') ? 'selected' : ''; ?>>April</option>
                <option value="May" <?php echo (isset($toMonth) && $toMonth === 'May') ? 'selected' : ''; ?>>May</option>
                <option value="June" <?php echo (isset($toMonth) && $toMonth === 'June') ? 'selected' : ''; ?>>June</option>
                <option value="July" <?php echo (isset($toMonth) && $toMonth === 'July') ? 'selected' : ''; ?>>July</option>
                <option value="August" <?php echo (isset($toMonth) && $toMonth === 'August') ? 'selected' : ''; ?>>August</option>
                <option value="September" <?php echo (isset($toMonth) && $toMonth === 'September') ? 'selected' : ''; ?>>September</option>
                <option value="October" <?php echo (isset($toMonth) && $toMonth === 'October') ? 'selected' : ''; ?>>October</option>
                <option value="November" <?php echo (isset($toMonth) && $toMonth === 'November') ? 'selected' : ''; ?>>November</option>
                <option value="December" <?php echo (isset($toMonth) && $toMonth === 'December') ? 'selected' : ''; ?>>December</option>
            </select>
        </div>
        <?php 
            if (isset($_POST['submitRecord'])) {
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
            
                    $selectedMonthsString = implode(", ", $selectedMonths);
                    
                    $stmt = $conn->prepare("UPDATE attendance_year SET school_year=?, months=?, from_month=?, to_month=? WHERE year_id=?");
                    $stmt->bind_param("sssss", $school_year, $selectedMonthsString, $fromMonth, $toMonth, $year_id);
                    $result = $stmt->execute();
                    $stmt->close();
                    if ($result) {
                        // Redirect to prevent multiple insertions
                        history($_SESSION['control_number'], "Settings", "School Year Updated");
                        echo '<script>window.location="add_school_year.php"</script>';
                        exit();
                    } else {
                        echo "Error: " . $stmt->error; // Use $stmt->error instead of mysqli_error($conn)
                    }
                } else {
                    echo "Invalid range of months selected";
                }

            }
        ?>
        <br>
        <div>
        <button type="submit" name="submitRecord">Save</button>
        <button type="button" id="cancel" onclick="redirect()">Cancel</button>
        <button type="submit" name="deleteRecord" id="deleteButton" class="right-label">Delete</button>
        </div>
    </form>
</body>

<script>
    function redirect() {
        <?php
            echo "window.location='add_school_year.php';";
        ?>
    }
    document.getElementById("deleteButton").addEventListener("click", function(event) {
        var result = confirm("Are you sure you want to delete this record?");
        if (!result) {
            event.preventDefault(); // Cancel the form submission if not confirmed
        }
    });
</script>

</html>
