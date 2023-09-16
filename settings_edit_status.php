<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    
    include 'navbar_hris.php';
    change_default();

    if (isset($_GET['value_id'])) {
        $value_id = $_GET['value_id'];   
    }

    $sql = "SELECT * FROM data_values WHERE value_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $value_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $data_value = $row['data_value'];
        $data_type = $row['data_type'];
    }
    else {
        echo "No data found.";
    }

    if (isset($_POST['deleteRecord'])) {
        $sql = "SELECT * FROM employees WHERE department = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data_value);
        $stmt->execute();
        $result = $stmt->get_result();
        $numRows = $result->num_rows;

        if ($numRows == 0) {
            $sql = "DELETE FROM data_values WHERE value_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $value_id);
            $stmt->execute();
            $stmt->close();
    
            
            // Redirect to the appropriate page after deletion
            if ($data_type == "emp_status") {
                history($_SESSION['control_number'], "Settings", "Employment Status Value Deleted");
                echo '<script>window.location="settings_status_values.php?"</script>';
            }
            if ($data_type == "classification") {
                history($_SESSION['control_number'], "Settings", "Classification Value Deleted");
                echo '<script>window.location="settings_classification_values.php?"</script>';
            }
            if ($data_type == "department") {
                history($_SESSION['control_number'], "Settings", "Department Value Deleted");
                echo '<script>window.location="settings_department_values.php?"</script>';
            }
            
            exit();
        }
        else {
            echo "<script>alert('Cannot delete this record. There are employees assigned to this department.');</script>";
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
        <?php 
            if ($data_type == "emp_status") {
                echo "<h1>Edit Employment Status Value</h1>";
            }
            if ($data_type == "classification") {
                echo "<h1>Edit Classification Value</h1>";
            }
            if ($data_type == "department") {
                echo "<h1>Edit Department Value</h1>";
            }
            echo "<br>";
            if ($data_type == "emp_status") {
                echo '<label for="value">Employment Status Value:</label>';
            }
            if ($data_type == "classification") {
                echo '<label for="value">Classification Value:</label>';
            }
            if ($data_type == "department") {
                echo '<label for="value">Department Value:</label>';
            }
        ?>
        
        <input type="text" name="value" id="value" placeholder="Enter Value" value="<?php echo isset($data_value) ? $data_value : '' ?>"><br>
        
        <?php 
            if (isset($_POST['submitRecord'])) {
                $value = $_POST['value'];
        
                $sql = "UPDATE data_values SET data_value = ? WHERE value_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $value, $value_id);
                $stmt->execute();
                $stmt->close();
                
                

                if ($data_type == "emp_status") {
                    if ($data_value != $value) {
                        history($_SESSION['control_number'], "Settings", "Employment Status Value Updated");
                    }
                    echo '<script>window.location="settings_status_values.php?"</script>';
                }
                if ($data_type == "classification") {
                    if ($data_value != $value) {
                        history($_SESSION['control_number'], "Settings", "Classification Value Updated");
                    }
                    echo '<script>window.location="settings_classification_values.php?"</script>';
                }
                if ($data_type == "department") {
                    if ($data_value != $value) {
                        history($_SESSION['control_number'], "Settings", "Department Value Updated");
                    }
                    echo '<script>window.location="settings_department_values.php?"</script>';
                }
                
                exit();
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
        if ($data_type == "emp_status") {
            echo "window.location='settings_status_values.php';";
        }
        if ($data_type == "classification") {
            echo "window.location='settings_classification_values.php';";
        }
        if ($data_type == "department") {
            echo "window.location='settings_department_values.php';";
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
