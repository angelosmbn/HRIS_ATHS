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
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Settings</h1>
        <table>
            <tr>
                <th>
                    <span>Name:</span> <?php echo $_SESSION['name'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                    <span>Username:</span> <?php echo $_SESSION['username'] ?>
                </th>
                <th><img src="images/<?php echo $_SESSION['image'] ?>" alt="No Image" class="right-label"></th>
            </tr>
        </table>
        <a href="settings_change_username.php">Change Username</a><br>
        <a href="settings_change_password.php">Change Password</a><br>
        <a href="settings_change_profile.php">Change Profile</a><br>
        <a href="settings_status_values.php">Employment Status Values</a><br>
        <a href="settings_classification_values.php">Classification Values</a><br>
        <a href="settings_department_values.php">Department Values</a><br>
        <?php 
            if ($_SESSION['access_level'] == 'super admin'){
                echo "<a href='manage_admin.php'>Manage Admin</a>";
            }
        ?>
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
