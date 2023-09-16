<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
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
        max-height: 700px;
        overflow: auto;
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
    span {
        font-weight: bold;
    }
    #reset_leave{
        color: red;
        background: none;
        border: none;
        font-size: 20px;
        text-decoration: underline;
        
    }
    button{
        cursor: pointer;
    }
    table.options {
        margin-top: 0px;
        display: absolute;
        border-collapse: separate;
        width: 100%;
        font-size: 20px;
        border-spacing: 20px 20px;
        text-align: center;
    }
    table.options th {
        font-weight: normal;
        width: 33%;
        
    }
    table.options td {
        font-weight: bold;
        padding-top: 30px;
    }

</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Settings</h1>
        <table>
            <tr>
                <th>
                    <span>Name:</span> <?php echo $_SESSION['fname'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                </th>
                <th><img src="images/<?php echo $_SESSION['image'] ?>" alt="" class="right-label"></th>
            </tr>
        </table>
        <table class="options">
            <tr>
                <td colspan="3">Account Settings</td>
            </tr>
            <tr>
                <th><a href="settings_change_username.php">Change Username</a><br>
                <th> <a href="settings_change_password.php">Change Password</a><br>
                <?php if ($_SESSION['access_level'] == 'super admin' || $_SESSION['access_level'] == 'admin') { ?>
                <th><a href="settings_change_profile.php">Change Profile</a><br>
            </tr>
            <tr>
                <td colspan="3">Input Settings</td>
            </tr>
            <tr>
                <th><a href="settings_status_values.php">Employment Status Values</a><br>
                <th><a href="settings_classification_values.php">Classification Values</a><br>
                <th><a href="settings_department_values.php">Department Values</a><br>
                <?php } ?>
            </tr>
            <tr>
                <?php 
                    
                    if ($_SESSION['access_level'] == 'super admin'){
                        echo "<td colspan='3'>Other Settings</td>
                        </tr>
                        <tr>";
                        //echo "<a href='increment_years.php'>Increment Years of Service</a><br>";
                        echo '<th><button id="reset_leave" onclick="resetLeaveConfirmation()">Reset Leave Credits</button><br>';
                        echo '<th colspan="2"><button id="reset_leave" onclick="deleteAttendanceRecordConfirmation()">Delete All Attendance Record</button>';
                    }
                ?>
            </tr>
        </table>
    </form>
</body>

<script>
    function redirectToSettings() {
        <?php
        echo "window.location.href = 'settings.php';";
        ?>
    }

    function resetLeaveConfirmation() {
        if (confirm("Are you sure you want to reset leave credits?")) {
            var input = prompt("To confirm, type 'RESET LEAVE'");
            
            if (input === null) {
                // User canceled the input
                alert("Leave credits will not be reset.");
            } else if (input.trim() === "RESET LEAVE") {
                // User confirmed and provided the correct input
                window.location.href = "reset_leave.php"; // Replace with the actual file name or URL to perform the leave reset
                alert("Successfully Reset Leave Credits.");
            } else {
                alert("Wrong Input. Leave credits will not be reset.");
            }
        } else {
            alert("Leave credits will not be reset.");
        }
    }
    function deleteAttendanceRecordConfirmation() {
        if (confirm("Are you sure you want to delete attendance recrod?")) {
            var input = prompt("To confirm, type 'DELETE ATTENDANCE RECORD'");
            
            if (input === null) {
                // User canceled the input
                alert("Attendance Record will not be deleted.");
            } else if (input.trim() === "DELETE ATTENDANCE RECORD") {
                // User confirmed and provided the correct input
                window.location.href = "delete_attendance.php"; // Replace with the actual file name or URL to perform the leave reset
                alert("Successfully Deleted Attendance Record.");
            } else {
                alert("Wrong Input. Attendance Record will not be deleted.");
            }
        } else {
            alert("Attendance Record will not be deleted.");
        }
    }

</script>



</html>
