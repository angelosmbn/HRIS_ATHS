<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }

    include 'navbar_hris.php';

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

    .records table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
        border: 1px solid #ccc;
    }

    .records th, .records td, .records tr {
        display: relative;
        text-align: left;
        padding: 8px;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
        border: 1px solid #ccc;
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
        width: 95%;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
    #recordInputs {
        display: none;
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
        margin-top: 10px;
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

</style>
</head>
<body>
    

    <form action="" method="POST" enctype="multipart/form-data">
        <h1>
            <span style="color: black;">Manage Admin</span>  
        </h1>
        <div>
            <button type="button" id="add_record" onclick="toggleInputs()">Add Admin</button>
            <button type="button" id="back" onclick="redirectToSettings()">Back</button>
        </div>
        <div id="recordInputs" style="display: none;">
            <table>
                <tr>
                    <td><label for="surname">Surname: </label></td>
                    <td><label for="name">Name: </label></td>
                    <td><label for="middle_name">Middle name: </label></td>
                </tr>

                <tr>
                    <td><input type="text" name="surname" id="surname" placeholder="Enter Surname" required></td>
                    <td><input type="text" name="name" id="name" placeholder="Enter Name" required></td>
                    <td><input type="text" name="middle_name" id="middle_name" placeholder="Enter Middle Name" required></td>
                </tr>
            </table>

            <table>
                <tr>
                    <td><label for="control_number">Control Number: </label></td>
                    <td><label for="fileToUpload">Upload Image: </label></td>
                    <td><label for="access_level">Access Level: </label></td>
                </tr>

                <tr>
                    <td><input type="text" name="control_number" id="control_number" placeholder="Enter Control Number" required></td>
                    <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
                    <td>
                        <select name="access_level" id="access_level" >
                            <option value="">Select Access Level</option>
                            <option value="super admin">Super Admin</option>
                            <option value="admin">Admin</option>
                        </select>
                    </td>
                </tr>
            </table>
            
            <br><button type="submit" name="submitRecord" id="submitRecord">Save</button>
        </div>

        <table class="records">
            <thead>
                <tr class="fixed-row">
                    <th>#</th>
                    <th>Control Number</th>
                    <th>Surname</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Access Level</th>
                    <th>Status</th>
                </tr>
            </thead>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
                    $control_number = $_POST['control_number'];
        
                    // Check if the control number is already used
                    $checkSql = "SELECT * FROM user WHERE control_number = ?";
                    $checkStmt = $conn->prepare($checkSql);
                    $checkStmt->bind_param("s", $control_number);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    $checkStmt->close();

                    if ($checkResult->num_rows > 0) {
                        // Control number is already used, show an error message
                        echo '<br><span style="color: red;">Error: Control number is already used.</span>';
                    } else {
                        $surname = $_POST['surname'];
                        $name = $_POST['name'];
                        $middle_name = $_POST['middle_name'];
                        $access_level = $_POST['access_level'];
                        $default_password = sha1($control_number);

                        $stmt = $conn->prepare("INSERT INTO user (control_number, surname, name, middle_name, username, password, access_level) VALUES (?, ?, ?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssssss", $control_number, $surname, $name, $middle_name, $control_number, $default_password, $access_level);
                        $result = $stmt->execute();  // Execute the statement and store the result

                        if ($result) {
                            // Redirect to prevent multiple insertions
                            if (!(isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK)) {
                                history($_SESSION['control_number'], $control_number, "Admin Added");
                                echo '<script>window.location="manage_admin.php"</script>';
                                exit();
                            }
                        } else {
                            echo "Error: " . $stmt->error; // Use $stmt->error instead of mysqli_error($conn)
                        }
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
            
                                history($_SESSION['control_number'], $control_number, "Admin Added, Image Uploaded");
                                echo '<script>
                                        alert("Profile successfully updated!");
                                        window.location="manage_admin.php"
                                      </script>';
                                exit();
                            }
                        }
                    }


                }

                $sql = "SELECT * FROM user WHERE access_level != 'employee' ORDER BY access_level ASC";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $i . "</td>";
                        echo "<td><a href='edit_admin.php?control_number=" . $row['control_number'] . "'>" . $row['control_number'] . "</a></td>";
                        echo "<td>" . $row['surname'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['middle_name'] . "</td>";
                        echo "<td>" . $row['access_level'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
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
                row.addEventListener("click", function () {
                    // Remove the 'highlighted-row' class from all rows
                    rows.forEach(row => row.classList.remove("highlighted-row"));

                    // Add the 'highlighted-row' class to the clicked row
                    this.classList.add("highlighted-row");
                });

                row.addEventListener("dblclick", function () {
                    // Remove the 'highlighted-row' class from the double-clicked row
                    this.classList.remove("highlighted-row");
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
