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
    
    if (isset($_GET['service_id'])) {
        $service_id = $_GET['service_id'];   
    }else{
        echo '<script>
                alert("Please Select an Employee to Edit.");
                window.location="cur_emp.php"
            </script>';
    }

    $sql = "SELECT * FROM service_record WHERE service_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $control_number = $row['control_number'];
        $school_year = $row['school_year'];
        $status = $row['status'];
        $information = $row['information'];
    }
    else {
        echo "No data found.";
    }

    if (isset($_POST['deleteRecord'])) {
        $sql = "DELETE FROM service_record WHERE service_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $service_id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect to the appropriate page after deletion
        $type = "Service Record Deleted";
        history($_SESSION['control_number'], $control_number, $type);
        echo '<script>window.location="add_service_record.php?control=' . $control_number . '"</script>';
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
        height: 100vh;
        background-color: #fff;
        display: relative;
        padding: 1rem;
        margin-right: 20px;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin-left: 420px;
        border: 0.5px solid black;
        max-height: 820px;
        overflow-y: auto;
        width: 60%;
    }

    #information {
        width: 100%;
        resize: vertical;
        min-height: 550px;
        margin-top: 10px;
        font-size: 20px;
    }
    
    input[type="text"] {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
        font-size: 20px;
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
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Edit Service Record</h1>
        <input type="text" name="schoolYear" placeholder="School Year" value="<?php echo isset($school_year) ? $school_year : '' ?>"><br>
        <input type="text" name="status" placeholder="Status" value="<?php echo isset($status) ? $status : '' ?>"><br>
        <textarea name="information" id="information" placeholder="Load / Teaching / Non-Teaching" rows="4"><?php echo isset($information) ? $information : '' ?></textarea><br>
        
        <?php 
            if (isset($_POST['submitRecord'])) {
                $new_school_year = $_POST['schoolYear'];
                $new_status = $_POST['status'];
                $new_information = $_POST['information'];
        
                $sql = "UPDATE service_record SET school_year = ?, status = ?, information = ? WHERE service_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $new_school_year, $new_status, $new_information, $service_id);
                $stmt->execute();
                $stmt->close();
                
                if ($new_school_year != $school_year || $new_status != $status || $new_information != $information) {
                    $type = "Service Record Updated";
                    history($_SESSION['control_number'], $control_number, $type);
                }
                echo '<script>window.location="add_service_record.php?control=' . $control_number . '"</script>';
            }
        ?>
        <button type="submit" name="submitRecord">Save</button>
        <button type="button" id="cancel" onclick="redirectToServiceRecord()">Cancel</button>
        <button type="submit" name="deleteRecord" id="deleteButton" class="right-label">Delete</button>
    </form>
</body>

<script>
    function redirectToServiceRecord() {
        <?php
        if (isset($control_number)) {
            echo "window.location.href = 'add_service_record.php?control=$control_number';";
        } else {
            echo "window.location.href = 'cur_emp.php';";
        }
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
