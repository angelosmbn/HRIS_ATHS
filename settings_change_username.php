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
<?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord']) && $_POST['username'] != "") {
                $username = sha1($_POST['username']);

                $sql = "SELECT * FROM user WHERE username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if (strlen($_POST['username']) >= 6){
                    if ($username != $_SESSION['username']) {
                        if ($result->num_rows == 0) {
                            if ($username != sha1($_SESSION['control_number'])) {
                                $sql = "UPDATE user SET username = ? WHERE control_number = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ss", $username, $_SESSION['control_number']);
                                $stmt->execute();
                                $stmt->close();
                                
                                $success_message = "Successfully changed username.";
                                $type = "Username Update";
                                history($_SESSION['control_number'], "Settings", $type);
                                $_SESSION['username'] = $username;
                            }
                            else{
                                $error_message = "Username cannot be the same as the control number.";
                            }
                        }
                        else{
                            $error_message = "Username already exists.";
                        }
                    }
                    else{
                        $error_message = "Username is the same as the old one.";
                    }
                }
                else {
                    $error_message = "Username must be at least 6 characters.";
                }
                
            }
            else{
                if (isset($_POST['submitRecord'])){
                    $error_message = "Fill up all the sections.";
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
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Change Username</h1>
        <table>
            <tr>
                <th>
                    <span>Name:</span> <?php echo $_SESSION['fname'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                </th>
                <th><img src="images/<?php echo $image ?>" alt="No Image" class="right-label"></th>
            </tr>
        </table>
        <br><br>
        <label for="username">New Username:</label>
        <input type="text" name="username" id="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>">

        
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
        <button type="submit" name="submitRecord">Change Username</button>
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
    if (check_default() == 1 && !isset($success_message)) {
        echo '<script>
            alert("Please Change Your Default Username.");
            </script>';
    }
    if (check_default() != 1 && check_default() != 0) {
        change_default();
    }
?>