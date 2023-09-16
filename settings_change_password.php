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
    }

    th img{
        width: 110px;
        height: 110px;
        border: 1px;
        margin-right: 10px
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
    span {
        font-weight: bold;
    }
    label {
        font-weight: bold;
    }
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Change Password</h1>
        <table>
            <tr>
                <th>
                    <span>Name:</span> <?php echo $_SESSION['fname'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                </th>
                <th><img src="images/<?php echo $image ?>" alt="No Image" class="right-label"></th>
            </tr>
        </table><br><br>
        <label for="current_password">Current Password:</label>
        <input type="password" name="current_password" id="current_password" placeholder="Enter Current Password">
        <br>
        <label for="new_password">New Password:</label>
        <input type="password" name="new_password" id="new_password" placeholder="Enter New Password">
        <br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password">

        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord']) && $_POST['current_password'] != "" && $_POST['new_password'] != "" && $_POST['confirm_password'] != "") {
                $current_password = sha1($_POST['current_password']);
                $new_password = sha1($_POST['new_password']);
                $confirm_password = sha1($_POST['confirm_password']);

                if ($current_password == $_SESSION['password']) {
                    if (strlen($_POST['new_password']) >= 8){
                        if ($new_password == $confirm_password) {
                            if ($new_password == $current_password){
                                $error_message = "New password cannot be the same as the current password.";
                            }
                            else{
                                $sql = "SELECT birthday FROM employees WHERE control_number = '" . $_SESSION['control_number'] . "'";
                                $result = $conn->query($sql);
                                $row = $result->fetch_assoc();
                                $birthday = $row['birthday'];

                                if ($new_password != sha1($birthday)) {
                                    $sql = "UPDATE user SET password = ? WHERE control_number = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("ss", $new_password, $_SESSION['control_number']);
                                    $stmt->execute();
                                    $stmt->close();

                                    $success_message = "Successfully changed password.";
                                    
                                    $type = "Password Update";
                                    history($_SESSION['control_number'], "Settings", $type);

                                    $_SESSION['password'] = $new_password;
                                    unset($current_password, $new_password, $confirm_password);
                                }
                                else{
                                    $error_message = "New password cannot be the same as your birthday.";
                                }
                            }
                        }
                        else {
                            $error_message = "New password and confirm password do not match.";
                        }
                    }
                    else {
                        $error_message = "Password must be at least 8 characters.";
                    }

                }
                else {
                    $error_message = "Current password is incorrect.";
                }
            }else{
                if (isset($_POST['submitRecord'])){
                    $error_message = "Fill up all the sections.";
                }
            }
        ?>
        <br>
        <?php 
            if (isset($error_message)) {
                echo "<span style='color: red'>$error_message</span><br>";
            }
            else if (isset($success_message)) {
                echo "<span style='color: green'>$success_message</span><br>";
            }
        ?>
        <div>
            <button type="submit" name="submitRecord">Change Password</button>
            <button type="button" id="cancel" onclick="redirectToSettings()"><?php echo isset($success_message) ? "Back" : "Cancel" ?></button>
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
<?php 
    if (check_default() == 2 && !isset($success_message)) {
        echo '<script>
            alert("Please Change Your Default Password.");
            </script>';
    }
    if (check_default() != 2 && check_default() != 0) {
        change_default();
    }
?>