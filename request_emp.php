<?php 
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }else{
        $image = $_SESSION['image'];
    }
    if ($_SESSION['access_level'] != 'employee') {
        echo '<script>
                alert("Invalid Access.");
                window.location="Admin_home_hris.php";
            </script>';
        exit;
    }
    include 'navbar_hris.php';
    change_default();
    

    function recent_request($employee_id, $type) {
        $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');

        //Get Employee Name
        $get_employee_name = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
        $get_employee_name->bind_param("s", $employee_id);
        $get_employee_name->execute();
        $result_employee_name = $get_employee_name->get_result();
        if ($result_employee_name->num_rows > 0) {
          $employee = $result_employee_name->fetch_assoc();
          $employee_name = $employee['surname'] . ", " . $employee['name'];
          if ($employee['middle_name'] !== "") {
            $employee_name .= " " . $employee['middle_name'][0] . ".";
          }
          if ($employee['suffix'] !== "") {
            $employee_name .= " " . $employee['suffix'];
          }
        }else{
          $employee_name = "Unknown";
        }
        $admin_id = "Not Applicable";
        $admin_name = "Not Applicable";
        $stmt = $conn->prepare("INSERT INTO recent_request (admin_id, employee_id, type, admin_name, employee_name)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $admin_id, $employee_id, $type, $admin_name, $employee_name);
        $stmt->execute();
        $stmt->close();
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
    
    input, textarea{
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid black;
        border-radius: 3px;
    }
    textarea {
        max-width: 100%;
        max-height: 100%;
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
    .labels {
        font-size: 20px;
    }
</style>

</head>

<body>
    <form action="" method="POST">
        <h1>Request</h1>
        <table>
            <tr>
                <th class="labels">
                    <span>Name:</span> <?php echo $_SESSION['fname'] ?><br>
                    <span>Control Number</span>: <?php echo $_SESSION['control_number'] ?><br>
                </th>
                <th><img src="images/<?php echo $image ?>" alt="No Image" class="right-label"></th>
            </tr>
        </table><br><br>
        <label for="request">Request:</label>
        <input type="text" name="request" id="request" placeholder="Enter Your Request" required>
        <br>
        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Enter Description"></textarea>
        <br>
        <label for="date_needed">Date Needed:</label>
        <input type="date" name="date_needed" id="date_needed" required min="<?php echo date('Y-m-d'); ?>">

        <?php 
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
                $request = $_POST['request'];
                $description = $_POST['description'];
                $date_needed = $_POST['date_needed'];

                $sql = "INSERT INTO request (control_number, name, req, description, date_needed, status) 
                VALUES ('" . $_SESSION['control_number'] . "', '" . $_SESSION['fname'] . "', '" . $request . "', '" . $description . "', '" . $date_needed . "', 'pending')";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    $success_message = "Request Sent Successfully!";
                    $type = "Pending Request";
                    recent_request($_SESSION['control_number'], $type);
                }else{
                    $error_message = "Error in sending request.";
                }
            }
        ?>
        <br>
        <?php 
            if (isset($error_message)) {
                echo "<script>alert('$error_message');
                window.location.href='request_emp.php';
                </script>";
            }
            else if (isset($success_message)) {
                echo "<script>alert('$success_message');
                window.location.href='request_emp.php';
                </script>";
            }
        ?>
        <div>
            <button type="submit" name="submitRecord">Send Request</button>
            <button type="button" id="cancel" onclick="redirectToSettings()"><?php echo isset($success_message) ? "Back" : "Cancel" ?></button>
        </div>
    </form>
</body>

<script>
    function redirectToSettings() {
        // Assuming you have a PHP variable called $_SESSION['control_number']
        var controlNumber = <?php echo json_encode($_SESSION['control_number']); ?>;
        window.location.href = 'information.php?control=' + controlNumber;
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