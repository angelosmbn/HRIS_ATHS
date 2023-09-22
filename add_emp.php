<?php
    session_start(); // Start the session

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }
    if ($_SESSION['access_level'] == 'employee') {
        echo '<script>
                alert("Invalid Access.");
                window.location="information.php?control=' . $_SESSION['control_number'] . '";
            </script>';
        exit;
    }
    include 'navbar_hris.php';
    change_default();
    
    $error_message = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $control_number = $_POST['control_number'];
        $surname = $_POST['surname'];
        $name = $_POST['name'];
        $middle_name = $_POST['middle_name'];
        $suffix = $_POST['suffix'];
        $birthday = $_POST['birthday'];
        $civil_status = $_POST['civil_status'];
        $gender = $_POST['gender'];
        $employment_status = $_POST['employment_status'];
        $classification = $_POST['classification'];
        $department = $_POST['department'];
        $date_hired = $_POST['date_hired'];
        $salary = $_POST['salary'];

        $currentDate = date("Y-m-d");
        $years_service = date_diff(date_create($date_hired), date_create($currentDate))->y;
        $age = date_diff(date_create($birthday), date_create($currentDate))->y;

        $address = $_POST['address'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $course_taken = $_POST['course_taken'];
        $further_studies = $_POST['further_studies'];
        $number_units = $_POST['number_units'];
        $prc_number = $_POST['prc_number'];
        $prc_exp = $_POST['prc_exp'];
        $position = $_POST['position'];
        $tin = $_POST['tin'];
        $sss = $_POST['sss'];
        $philhealth = $_POST['philhealth'];
        $pagibig = $_POST['pagibig'];

        if ($conn->connect_error) {
            $error_message = 'Unable to connect to the database. Please try again later.';
            die("Connection failed: " . $conn->connect_error);
        }
        else {
            $stmt = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
            $stmt->bind_param("s", $control_number);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            if ($result->num_rows > 0) {
                $error_message = 'Control Number already exists. Please try again.';
            }
            else{
                if ($control_number == "RA") {
                    // Convert the date string to a DateTime object
                    $date = new DateTime($date_hired);
                    // Extract the year from the DateTime object
                    $year = $date->format("Y");
                
                    $sql = "SELECT * FROM employees WHERE control_number LIKE 'RA%'";
                    $result = mysqli_query($conn, $sql);
                    $total = mysqli_num_rows($result);
                
                    $control_number = "RA-" . $year . "-" .($total + 1);
                }
                
                $stmt = $conn->prepare("INSERT INTO employees (control_number, surname, name, middle_name, suffix, birthday, age, civil_status, gender,
                                                                employment_status, classification, date_hired, years_in_service, address, contact,
                                                                email, course_taken, further_studies, number_of_units, prc_number, prc_exp,
                                                                position, tin, sss, philhealth, pag_ibig, department, salary)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssisssssissssssssssssssd", $control_number, $surname, $name, $middle_name, $suffix, $birthday, $age, $civil_status, $gender, $employment_status, $classification, $date_hired, $years_service, $address, $contact, $email, $course_taken, $further_studies, $number_units, $prc_number, $prc_exp, $position, $tin, $sss, $philhealth, $pagibig, $department, $salary);
                $stmt->execute();
                $stmt->close();

                $access_level = "employee";
                $status = "active";
                $encrypted_username = sha1($control_number);
                $encrypted_password = sha1($birthday);
                $stmt = $conn->prepare("INSERT INTO user (control_number, surname, name, middle_name, suffix, username, password, access_level, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $control_number, $surname, $name, $middle_name, $suffix, $encrypted_username, $encrypted_password, $access_level, $status);
                $stmt->execute();
                $stmt->close();
                
                updateSLVL($conn, $control_number, $employment_status, $classification);

                $success_message = "Successfully added employee.";

                $type = "Employee Added";

                if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK) {
                    $path = "images/";
                    $filename = $_FILES['fileToUpload']['name'];
                    $timestamp = time(); // Get the current timestamp
                    
                    $extension = pathinfo($filename, PATHINFO_EXTENSION);
                    $uniqueFilename = $timestamp . '_' . $filename; // Combine timestamp and original filename
                    
                    // Retrieve the existing image filename from the database
                    $oldImageFilename = ''; // Initialize the variable
                    $stmt = $conn->prepare("SELECT image FROM employees WHERE control_number = ?");
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
                            $stmt = $conn->prepare("UPDATE employees SET image = ? WHERE control_number = ?");
                            $stmt->bind_param("ss", $uniqueFilename, $control_number);
                            $stmt->execute();
                            $stmt->close();
        
                            if (isset($type)){
                                $type .= ", Profile Uploaded";
                            }
                            else{
                                $type = "Profile Uploaded";
                            }

                            $stmt = $conn->prepare("UPDATE user SET image = ? WHERE control_number = ?");
                            $stmt->bind_param("ss", $uniqueFilename, $control_number);
                            $stmt->execute();
                            $stmt->close();
                            
                        }
                    }
                }
                if (isset($type)){
                    history($_SESSION['control_number'], $control_number, $type);
                    echo '<script>
                        alert("Successfully Added Employee!");
                        window.location="add_emp.php?control=' . $control_number . '"
                        </script>';
                    exit;
                }
            }
        }
    } 

    if (isset($_GET['control'])) {
        $sql = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
        $sql->bind_param("s", $_GET['control']);
        $sql->execute();
        $result = $sql->get_result();
        $sql->close();

        while ($row = $result->fetch_assoc()) {
            $control_number = $row['control_number'];
            $surname = $row['surname'];
            $name = $row['name'];
            $middle_name = $row['middle_name'];
            $suffix = $row['suffix'];
            $birthday = $row['birthday'];
            $civil_status = $row['civil_status'];
            $gender = $row['gender'];
            $employment_status = $row['employment_status'];
            $classification = $row['classification'];
            $date_hired = $row['date_hired'];
            $salary = $row['salary'];
            $years_service = $row['years_in_service'];
            $address = $row['address'];
            $contact = $row['contact'];
            $email = $row['email'];
            $course_taken = $row['course_taken'];
            $further_studies = $row['further_studies'];
            $number_units = $row['number_of_units'];
            $prc_number = $row['prc_number'];
            $prc_exp = $row['prc_exp'];
            $position = $row['position'];
            $tin = $row['tin'];
            $sss = $row['sss'];
            $philhealth = $row['philhealth'];
            $pagibig = $row['pag_ibig'];
            $department = $row['department'];
        }
    }

    if (isset($control_number) && isset($success_message) || isset($_GET['control'])){
        $stmt = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
        $stmt->bind_param("s", $control_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            $success_message = "Successfully Added Employee.";
        }
    }
?>


<!DOCTYPE html>
<html>
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
            height: 100vh;
            background-color: #fff;
            display: relative;
            padding: 1rem;
            width: 95%;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            margin: auto;
            border: 0.5px solid black;
            height: 850px;
            overflow: auto;
        }

        input, select{
            width: 100%;
            height: 40px;
            font-size: 17px;
            background-color: #f7f7f7;
            box-sizing: border-box;
            padding-left: 8px;
            border: 1px solid #999;
        }

        #fileToUpload {
            background-color: #fff;
            border: none;
        }
        .submission {
            position: relative;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .error-message {
            text-align: center;
            color: red;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .success-message {
            text-align: center;
            color: green;
            font-size: 20px;
            margin-bottom: 20px;
        }
        .button{
            margin-top: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .spacing{
            
        }
        table{
            border-collapse: separate;
            width: 100%;
            font-size: 20px;
            border-spacing: 10px 0;
            margin-bottom: 20px;
        }
        th, td, tr{
            text-align: left;            
        }
        #control_number_th{
            width: 14%;
        }
        #th_2{
            width: 25%;
        }
        #th_3{
            width: 15%;
        }
        #dates_th{
            width: 7%;
        }
        #years_service_th{
            width: 10%;
        }
        #civil_th{
            width: 10%;
        }
        #prc_exp_th{
            width: 12%;
        }
        #thirty{
            width: 25%;
        }
        #salary_th{
            width: 10%;
        }

        .submit-button {
        display: inline-block;
        padding: 10px 20px;
        margin: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        font-size: 16px;
        cursor: pointer;
        }

        /* Add hover effect */
        .submit-button:hover {
        background-color: #0056b3;
        }

        /* Add active (click) effect */
        .submit-button:active {
        background-color: #003974;
        }

        .preview-box {
            width: 120px;
            height: 120px;
            border: none; /* Remove the border */
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-box img {
            max-width: 100%;
            max-height: 100%;
        }

        #image-upload{
            max-width: 100%;
            max-height: 100%;
        }
        #upload {
            width: 20%;
        }
        #preview_col {
            width: 20%;
        }
        #button_col {
            width: 100%;
        }
        #suffix-th {
            width: 8%;
        }
        label {
            font-weight: 550; 
        }
    </style>
</head>
<body>
<div class="home-section">
<div class="home-content">
    <form method="POST" action="" autocomplete="off" enctype="multipart/form-data">
        <h2>Add Employee</h2><br>

        <table>
        <tr>
        <th><label for="control_number">Control Number:</label></th>
        <th><label for="surname">Surname:</label></th>
        <th><label for="name">Name:</label></th>
        <th><label for="middle_name">Middle Name:</label></th>
        <th id="suffix-th"><label for="suffix">Suffix:</label><br></th>
        </tr>

        <tr>
        <th id="control_number_th"><input type="text" name="control_number" id="control_number" maxlength="13" value="<?php echo isset($control_number) ? $control_number : '' ?>" required></th>
        <th><input type="text" name="surname" id="surname" maxlength="50" value="<?php echo isset($surname) ? $surname : '' ?>" required></th>
        <th><input type="text" name="name" id="name" maxlength="50" value="<?php echo isset($name) ? $name : '' ?>" required></th>
        <th><input type="text" name="middle_name" id="middle_name" maxlength="50" value="<?php echo isset($middle_name) ? $middle_name : '' ?>"></th>
        <th><input type="text" name="suffix" id="suffix" maxlength="20" value="<?php echo isset($suffix) ? $suffix : '' ?>"><br></th>
        </tr>
        </table>
        <table>
        <tr>
        <th><label for="birthday">Birthday:</label>
        <th><label for="civil_status" id="civil-tab">Civil Status:</label>
        <th><label for="gender" id="gender-tab">Gender:</label>
        <th><label for="employment_status" id="status-tab">Employment Status:</label> 
        <th><label for="classification" id="class-tab">Classification:</label>
        <th><label for="date_hired">Date Hired:</label>
        <?php 
            if (isset($years_service) && isset($success_message)){
                echo "<th><label for='years_service'>Years in Service:</label></th>";
            }else{
                echo "<br>";
            }
        ?>
        </tr>
        <tr>
        <th><input type="date" name="birthday" id="birthday" value="<?php echo isset($birthday) ? $birthday : '' ?>" required>
        <th><select name="civil_status" id="civil_status" required>
                <option value="">Select Civil Status</option>
                <option value="Single" <?php echo (isset($civil_status) && $civil_status === 'Single') ? 'selected' : ''; ?>>Single</option>
                <option value="Married" <?php echo (isset($civil_status) && $civil_status === 'Married') ? 'selected' : ''; ?>>Married</option>
                <option value="Widowed" <?php echo (isset($civil_status) && $civil_status === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
            </select>

            <th><select name="gender" id="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?php echo (isset($gender) && $gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo (isset($gender) && $gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                </select>

            <th><select name="employment_status" id="employment_status" required>
                <option value="">Select Employment Status</option>
                    <?php 
                        $data_type = "emp_status";
                        $sql = "SELECT * FROM data_values WHERE data_type = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $data_type);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['data_value'] . '" ' . (isset($employment_status) && $employment_status === $row['data_value'] ? 'selected' : '') . '>' . $row['data_value'] . '</option>';
                        }
                    ?>
                </select>

            <th>
                <select name="classification" id="classification" required>
                    <option value="">Select Classification</option>
                    <?php 
                        $data_type = "classification";
                        $sql = "SELECT * FROM data_values WHERE data_type = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $data_type);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['data_value'] . '" ' . (isset($classification) && $classification === $row['data_value'] ? 'selected' : '') . '>' . $row['data_value'] . '</option>';
                        }
                    ?>
                </select>
            </select>
            <th><input type="date" name="date_hired" id="date_hired" value="<?php echo isset($date_hired) ? $date_hired : '' ?>" required><br>
            <?php 
                if (isset($years_service) && isset($success_message)){
                    echo "<th><input type='text' name='years_service' id='years_service' value='" . $years_service . "' readonly></th>";
                }
            ?>
        </tr>
        </table>
        

        <table>
        <tr>
        <th id="salary_th"><label for="salary">Salary:</label></th>
        <th><label for="address">Address:</label>
        <th id="th_3"><label for="contact">Contact Number:</label>
        <th id="th_2"><label for="email">Email:</label><br>
        </tr>

        <tr>
        <th><input type="text" name="salary" id="salary" value="<?php echo isset($salary) ? $salary : '' ?>"></th>
        <th><input type="address" name="address" id="address" maxlength="250" value="<?php echo isset($address) ? $address : '' ?>">
        <th><input type="text" name="contact" id="contact"  maxlength="11" minlength="11" value="<?php echo isset($contact) ? $contact : '' ?>" >
        <th><input type="email" name="email" id="email" maxlength="100" value="<?php echo isset($email) ? $email : '' ?>" ><br>
        </tr>
        </table>


        <table>
        <tr>
        
        <th><label for="course_taken">Course Taken:</label>
        <th><label for="further_studies">Further Studies:</label>
        <th><label for="number_units">Number of Units:</label><br>
        </tr>

        <tr>
        
        <th><input type="text" name="course_taken" id="course_taken" maxlength="250" value="<?php echo isset($course_taken) ? $course_taken : '' ?>" >
        <th><input type="text" name="further_studies" id="further_studies" maxlength="250" value="<?php echo isset($further_studies) ? $further_studies : '' ?>">
        <th id="control_number_th"><input type="number" step="any" name="number_units" id="number_units" value="<?php echo isset($number_units) ? $number_units : '' ?>"><br>
        </tr>
        </table>

        <table>
        <tr>
        <th><label for="prc_number">PRC Number:</label>
        <th><label for="prc_exp">PRC Expiration:</label>
        <th><label for="position">Position:</label>
        <th><label for="tin">TIN:</label><br>
        </tr>

        <tr>
        <th id="thirty"><input type="text" name="prc_number" id="prc_number" maxlength="7" value="<?php echo isset($prc_number) ? $prc_number : '' ?>">
        <th id="prc_exp_th"><input type="date" name="prc_exp" id="prc_exp" value="<?php echo isset($prc_exp) ? $prc_exp : '' ?>">
        <th><input type="text" name="position" id="position" maxlength="100" value="<?php echo isset($position) ? $position : '' ?>" >
        <th id="thirty"><input type="text" name="tin" id="tin" maxlength="11" minlength="11" value="<?php echo isset($tin) ? $tin : '' ?>"><br>
        </tr>
        </table>

        <table>
        <tr>
        <th><label for="sss">SSS:</label>
        <th><label for="philhealth">PHILHEALTH:</label>
        <th><label for="pagibig">PAG-IBIG:</label>
        <th><label for="pagibig">Department:</label><br>
        </tr>

        <tr>
        <th><input type="text" name="sss" id="sss" maxlength="12" minlength="12" value="<?php echo isset($sss) ? $sss : '' ?>">
        <th><input type="text" name="philhealth" id="philhealth" maxlength="14" minlength="14" value="<?php echo isset($philhealth) ? $philhealth : '' ?>">
        <th><input type="text" name="pagibig" id="pagibig" maxlength="12" minlength="12" value="<?php echo isset($pagibig) ? $pagibig : '' ?>">
        <th>
            <select name="department" id="department" required>
                <option value="">Select Department</option>
                <?php 
                    $data_type = "department";
                    $sql = "SELECT * FROM data_values WHERE data_type = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $data_type);
                    $stmt->execute();
                    $result = $stmt->get_result();
                
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['data_value'] . '" ' . (isset($department) && $department === $row['data_value'] ? 'selected' : '') . '>' . $row['data_value'] . '</option>';
                    }
                    $conn->close();
                ?>
            </select>
        </th><br>
        </tr>
        </table>

        <table>
            <tr>
                <th><label for="image">Upload Image:</label></th>
                <th></th>
                <th></th>
            </tr>

            <tr>
                <th id="upload"><input type="file" name="fileToUpload" id="fileToUpload" onchange="previewImage();"></th>
                <th id="preview_col">
                <div class="preview-box" id="previewBox">
                    <?php 
                    if (isset($success_message) && isset($uniqueFilename)) {
                        echo '<img src="images/' . $uniqueFilename . '" id="image-upload">';
                    }
                    ?>
                    <img src="" id="previewImg">
                </div>

                </th>
                <th id="button_col">
                    <?php
                        if (isset($success_message)) {
                            echo '<div>';
                            if (isset($error_message)) {
                                unset($error_message);
                            }
                            if (isset($success_message)) {
                            echo '<div class="success-message">' . $success_message . '</div>';
                            }
                            echo'<div class="submission" id="buttons">';
                            echo "<a class='submit-button' href='edit_info.php?control={$control_number}'>Edit</a>";
                            echo '<a class="submit-button" href="add_emp.php">Add Another</a>';
                            echo '<a class="submit-button" href="cur_emp.php">Go To Employee</a>';
                            echo'</div>';
                            echo '</div>';
                        }
                        echo '<div>';
                        
                        if (!isset($success_message)) {
                            if (isset($error_message) && $error_message != '') {
                                echo '<div class="error-message">' . $error_message . '</div>';
                            }
                            echo'<div class="submission" id="buttons">';
                            echo '<button type="submit" id="submit" class="submit-button">Add Employee</button>';
                            echo'</div>';
                        }
                        echo '</div>';
                    ?>
                </th>
            </tr>
        </table>
    </form>
</div>
</div>   
</body>
<script>
    function previewImage() {
        var fileInput = document.getElementById('fileToUpload');
        var previewBox = document.getElementById('previewBox');
        var previewImg = document.getElementById('previewImg');

        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewBox.style.display = 'block';
            }

            reader.readAsDataURL(fileInput.files[0]);
        } else {
            previewImg.src = '';
            previewBox.style.display = 'none';
        }
    }

    // Function to disable input fields
    function disableInputFields() {
        var inputFields = document.querySelectorAll('#control_number, #surname, #name, #middle_name, #birthday, #civil_status, #gender, #employment_status, #classification, #date_hired, #years_service, #address, #contact, #email, #course_taken, #further_studies, #number_units, #prc_number, #prc_exp, #position, #tin, #sss, #philhealth, #pagibig, #fileToUpload, #department ,#suffix, #salary');

        inputFields.forEach(function(input) {
            input.disabled = true;
        });
    }

    // Call the function if $success_message is set
    <?php if (isset($success_message)) { ?>
        window.addEventListener('DOMContentLoaded', function() {
            disableInputFields();
        });
    <?php } ?>
</script>
</html>
