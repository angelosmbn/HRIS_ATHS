<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    if ($_SESSION['access_level'] != "super admin") {
        header("Location: Admin_home_hris.php");
        exit();
    }
    include 'navbar_hris.php';

    if (isset($_GET['control_number'])) {
        $control_number = $_GET['control_number'];   
    }
 

    $sql = "SELECT * FROM user WHERE control_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $control_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $surname = $row['surname'];
        $name = $row['name'];
        $middle_name = $row['middle_name'];
        $access_level = $row['access_level'];
        $status = $row['status'];
    }
    else {
        echo "No data found.";
    }

    if (isset($_POST['deleteRecord'])) {
        $sql = "DELETE FROM user WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $stmt->close();

        history($_SESSION['control_number'], $control_number, "Admin Deleted");
        echo '<script>window.location="manage_admin.php?"</script>';
        exit();
    }
        

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
        $new_control_number = $_POST['control_number'];
        
        // Check if the control number is already used
        $checkSql = "SELECT * FROM user WHERE control_number = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $new_control_number);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close();

        if ($checkResult->num_rows > 0 && $control_number != $new_control_number) {
            // Control number is already used, show an error message
            $error_message = "Control number is already used.";
        }
        else{
            $new_surname = $_POST['surname'];
            $new_name = $_POST['name'];
            $new_middle_name = $_POST['middle_name'];
            $new_access_level = $_POST['access_level'];
            $new_status = $_POST['status'];

            if ($new_surname != $surname || $new_middle_name != $middle_name || $new_name != $name || $new_control_number != $control_number || $new_access_level != $access_level || $new_status != $status) {
                $sql = "UPDATE user SET surname = ?, name = ?, middle_name = ?, control_number = ?, access_level = ?, status = ? WHERE control_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $new_surname, $new_name, $new_middle_name, $new_control_number, $new_access_level, $new_status, $control_number);
                $stmt->execute();
                $stmt->close();
                
                $control_number = $new_control_number;
                $success_message = "Successfully updated admin information.";

                $type = "Admin Updated";
                if (!(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK)) {
                    history($_SESSION['control_number'], $control_number, $type);
                }
            }
            else {
                $error_message = "No changes were made.";
            }

            if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
                $path = "images/";
                $filename = $_FILES['fileToUpload']['name'];
                $timestamp = time(); // Get the current timestamp
                
                $extension = pathinfo($filename, PATHINFO_EXTENSION);
                $uniqueFilename = $timestamp . '_' . $filename; // Combine timestamp and original filename
                
                // Retrieve the existing image filename from the database
                $oldImageFilename = ''; // Initialize the variable
                $stmt = $conn->prepare("SELECT image FROM user WHERE control_number = ?");
                $stmt->bind_param("s", $control_number);
                $stmt->execute();
                $stmt->bind_result($oldImageFilename);
                $stmt->fetch();
                $stmt->close();
                
                // Delete the existing image file from the server if it exists
                if ($oldImageFilename && file_exists($path . $oldImageFilename)) {
                    unlink($path . $oldImageFilename);
                }
                
                // Upload the new image file
                $status = move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $path . $uniqueFilename);
                
                if ($status) {
                    if ($conn->connect_error) {
                        $error_message = 'Unable to connect to the database. Please try again later.';
                        die("Connection failed: " . $conn->connect_error);
                    } else {
                        $stmt = $conn->prepare("UPDATE user SET image = ? WHERE control_number = ?");
                        $stmt->bind_param("ss", $uniqueFilename, $control_number);
                        $stmt->execute();
                        $stmt->close();

                        if (isset($type)){
                            $type .= ", Admin Profile Updated";
                        }
                        else{
                            $type = "Admin Profile Updated";
                        }
                        $_SESSION['image'] = $uniqueFilename;
                        history($_SESSION['control_number'], $control_number, $type);
                        echo '<script>
                                alert("Profile successfully updated!");
                                window.location="manage_admin.php"
                            </script>';
                        exit();
                    }
                }
            }
        }

    }
    $sql = "SELECT * FROM user WHERE control_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $control_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $surname = $row['surname'];
        $name = $row['name'];
        $middle_name = $row['middle_name'];
        $access_level = $row['access_level'];
        $status = $row['status'];
    }
    else {
        echo "No data found.";
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
    
    input, select {
        width: 100%;
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
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    th, td, tr {
        display: relative;
        text-align: left;
        padding: 8px;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
    }

    label {
        font-weight: bold;
    }
</style>

</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>Edit Admin</h1>
        <table>
                <tr>
                    <td><label for="surname">Surname: </label></td>
                    <td><label for="name">Name: </label></td>
                    <td><label for="middle_name">Middle name: </label></td>
                </tr>

                <tr>
                    <td><input type="text" name="surname" id="surname" placeholder="Enter Surname" value="<?php echo isset($surname) ? $surname : '' ?>" required></td>
                    <td><input type="text" name="name" id="name" placeholder="Enter Name" value="<?php echo isset($name) ? $name : '' ?>" required></td>
                    <td><input type="text" name="middle_name" id="middle_name" placeholder="Enter Middle Name" value="<?php echo isset($middle_name) ? $middle_name : '' ?>" required></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td><label for="control_number">Control Number: </label></td>
                    <td><label for="access_level">Access Level: </label></td>
                    <td><label for="status">Status: </label></td>
                </tr>

                <tr>
                    <td><input type="text" name="control_number" id="control_number" placeholder="Enter Control Number" value="<?php echo isset($control_number) ? $control_number : '' ?>" required></td>
                    <td>
                        <select name="access_level" id="access_level" >
                            <option value="">Select Access Level</option>
                            <option value="super admin" <?php echo (isset($access_level) && $access_level === 'super admin') ? 'selected' : ''; ?>>Super Admin</option>
                            <option value="admin" <?php echo (isset($access_level) && $access_level === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </td>
                    <td>
                        <select name="status" id="status" >
                            <option value="">Select Status</option>
                            <option value="active" <?php echo (isset($status) && $status === 'active') ? 'selected' : ''; ?>>Active</option>
                            <option value="disabled" <?php echo (isset($status) && $status === 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                        </select>
                    </td>
                    
                </tr>
            </table>
            <table>
                <tr>
                    <td><label for="fileToUpload">Upload Image: </label></td>
                </tr>

                <tr>
                    <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
                </tr>
            </table>
        
        
        <br>
        <?php 
            if (isset($success_message)) {
                echo "<p style='color: green;'>" . $success_message . "</p>";
            }
            elseif (isset($error_message)) {
                echo "<p style='color: red;'>" . $error_message . "</p>";
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
            echo "window.location='manage_admin.php';";
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
