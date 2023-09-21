<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }else{
        $image = $_SESSION['image'];
    }

    include 'navbar_hris.php';
    change_default();
?>
<?php 

    if (isset($_GET['success'])) {
        $success_message = urldecode($_GET['success']);
    }


            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
                $control_number = $_SESSION['control_number'];
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
                            $_SESSION['image'] = $uniqueFilename;
                            $success_message = "Image uploaded successfully.";
                            history($_SESSION['control_number'], $control_number, "Image Uploaded");
                            echo '<script>
                                alert("Successfully Updated Profile!");
                                window.location="settings_change_profile.php?success=' . $success_message . '"
                                </script>';
                            exit;
                        }
                    }
                    else{
                        $error_message = "Failed to upload image.";
                    }
                }
                if ($_FILES['fileToUpload']['error'] === UPLOAD_ERR_NO_FILE){
                    $error_message = "Please select an image to upload.";
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
    
    input {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid black;
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
        width: 200px;
    }

    #cancel {
        background-color: #f44336;
    }

    #cancel:hover {
        background-color: #f44336;
    }

    button[type="submit"]:hover {
        background-color: #3a8a3d;
    }

    .right-label {
        float: right;
        clear: right;
        margin-right: 10px
    }

    img{
        width: 110px;
        height: 110px;
        border: 1px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 16px;
        text-align: left;
    }
    th {
        font-weight: normal;
    }
    span, label {
        font-weight: bold;
    }
    .labels {
        font-size: 20px;
    }
</style>

</head>

<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>Change Profile</h1>
        <table>
            <tr>
                <th class="labels">
                    <span>Name:</span> <?php echo $_SESSION['fname'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                </th>
                <th><img src="images/<?php echo $image ?>" alt="No Image" class="right-label"></th>
            </tr>
        </table>
        <br><br>
        <label for="fileToUpload">Select A file</label>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <?php 
            if (isset($error_message)) {
                echo "<div style='color: red;'>" . $error_message . "</div>";
            }
            if (isset($success_message)) {
                echo "<div style='color: green;'>" . $success_message . "</div>";
            }
        ?>
        <br>
        <div>
            <button type="submit" name="submitRecord">Confirm</button>
            <button type="button" id="cancel" onclick="redirectToSettings()">Back</button>
        </div>
        
    </form>
</body>

<script>
    function redirectToSettings() {
        <?php
        echo "window.location.href = 'settings.php';";
        ?>
    }
</script>

</html>
