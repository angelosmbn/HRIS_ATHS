<?php
session_start(); // Start the session
//session_destroy(); // Destroy the session
if (isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
    header("Location: Admin_home_hris.php");
    exit();
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sha1($_POST['username']);
    $password = sha1($_POST['password']);
    $super_admin = "super admin";
    $admin_access = "admin";
    $user_access = "employee";
    $status = "active";

    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');
    if ($conn->connect_error) {
        $error_message = 'Unable to connect to the database. Please try again later.';
        die("Connection failed: " . $conn->connect_error);
    } else {
        $stmt_user = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ? AND status = ? AND (access_level = ? OR access_level = ?)");

        if (!$stmt_user) {
            $error_message = 'Unable to prepare the statement.';
        } else {
            $stmt_user->bind_param("sssss", $username, $password, $status ,$super_admin, $admin_access);
            $stmt_user->execute();
            $result_admin = $stmt_user->get_result();
            $stmt_user->close();

            if ($result_admin->num_rows > 0) {
                $user = $result_admin->fetch_assoc();
                $_SESSION['user'] = $user;
                header("Location: Admin_home_hris.php");
                exit();
            } else {
                $stmt_user = $conn->prepare("SELECT * FROM user WHERE username = ? AND password = ? AND access_level = ? AND status = ?");
                if (!$stmt_user) {
                    $error_message = 'Unable to prepare the statement.';
                } else {
                    $stmt_user->bind_param("ssss", $username, $password, $user_access, $status);
                    $stmt_user->execute();
                    $result_user = $stmt_user->get_result();
                    $stmt_user->close();

                    if ($result_user->num_rows > 0) {
                        $user = $result_user->fetch_assoc();
                        $_SESSION['user'] = $user;
                        header("Location: Admin_home_hris.php");
                        exit();
                    } else {
                        $query = "SELECT status FROM user WHERE username = '{$username}' AND password = '{$password}'";
                        $result = mysqli_query($conn, $query);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $status = isset($row['status']) ? $row['status'] : "disabled";
                            mysqli_free_result($result);
                        }
                        else {
                            echo "Error: " . $query . "<br>" . mysqli_error($conn);
                        }
                        $error_message = 'Invalid username or password1. Please try again.';
                        if ($status == "disabled" && isset($row['status'])) {
                            $error_message = 'This account is disabled.';
                        }
                    }
                }
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            display: flex; /* Use flex display for alignment */
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0; /* Remove default margin to avoid unexpected spacing */
            background-image: url("images/aths-background.jpg");
            background-size: cover; /* Ensure the background covers the entire body */
            background-repeat: no-repeat; /* Prevent background image from repeating */
            background-position: center; /* Center the background image */
        }

        .form {
            background-color: rgba(255, 255, 255, 0.5); /* 0.9 alpha for 90% opacity */
            display: block;
            padding: 1rem;
            max-width: 350px;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: auto;
            margin-top: 150px;
        }

        .form-title {
            font-size: 1.25rem;
            line-height: 1.75rem;
            font-weight: 600;
            text-align: center;
            color: #000;
        }

        .input-container {
            position: relative;
        }

        .input-container input, .form button {
            outline: none;
            border: 1px solid #e5e7eb;
            margin: 8px 0;
        }

        .input-container input {
            background-color: #fff;
            padding: 1rem;
            padding-right: 3rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            width: 250px;
            border-radius: 0.5rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .submit {
            display: block;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
            background-color: #4F46E5;
            color: #ffffff;
            font-size: 0.875rem;
            line-height: 1.25rem;
            font-weight: 500;
            width: 100%;
            border-radius: 0.5rem;
            text-transform: uppercase;
        }

        .signup-link {
            color: #6B7280;
            font-size: 0.875rem;
            line-height: 1.25rem;
            text-align: center;
        }

        .signup-link a {
            text-decoration: underline;
        }
        .error-message{
            color: red;
        }

        .copyright{
            margin-top: 30px;;
            text-align: center;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <form class="form" method="POST" action="">
        <p class="form-title">Sign in to your account</p>
        <div class="input-container">
            
            <input type="text" name="username" placeholder="Enter Username" required>
            <span></span>
        </div>
        <div class="input-container">
            <input type="password" name="password" placeholder="Enter password" required>
            <?php if (!empty($error_message)) { ?>
                <div class="error-message"><?php echo $error_message; ?></div>
            <?php } ?>
        </div>
        <button type="submit" class="submit">Sign in</button>
    </form>
</body>
</html>
