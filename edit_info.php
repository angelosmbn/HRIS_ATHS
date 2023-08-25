<?php
    session_start(); // Start the session

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
    }

    include 'navbar_hris.php';

    if (isset($_GET['control'])) {
        $control_number = $_GET['control'];
        if(isset($_GET['success_message'])) {
            $success_message = $_GET['success_message'];
        }
    }

    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_control_number = $_POST['control_number'];
       
        // Check if the control number is already used
        $checkSql = "SELECT * FROM employees WHERE control_number = ?";
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
            $resignation_date = isset($_POST['resignationDate']) ? $_POST['resignationDate'] : "";
            $surname = $_POST['surname'];
            $name = $_POST['name'];
            $middle_name = $_POST['middle_name'];
            $birthday = $_POST['birthday'];
            $civil_status = $_POST['civil_status'];
            $gender = $_POST['gender'];
            $employment_status = $_POST['employment_status'];
            $classification = $_POST['classification'];
            $date_hired = $_POST['date_hired'];
            $years_service = $_POST['years_service'];
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
                $unchanged_msg = '';
                if ($result->num_rows > 0) {
                    $rows = $result->fetch_assoc();
                    if (
                        $new_control_number != $rows['control_number'] ||
                        $surname != $rows['surname'] ||
                        $name != $rows['name'] ||
                        $middle_name != $rows['middle_name'] ||
                        $birthday != $rows['birthday'] ||
                        $civil_status != $rows['civil_status'] ||
                        $gender != $rows['gender'] ||
                        $employment_status != $rows['employment_status'] ||
                        $classification != $rows['classification'] ||
                        $date_hired != $rows['date_hired'] ||
                        $years_service != $rows['years_in_service'] ||
                        $address != $rows['address'] ||
                        $contact != $rows['contact'] ||
                        $email != $rows['email'] ||
                        $course_taken != $rows['course_taken'] ||
                        $further_studies != $rows['further_studies'] ||
                        $number_units != $rows['number_of_units'] ||
                        $prc_number != $rows['prc_number'] ||
                        $prc_exp != $rows['prc_exp'] ||
                        $position != $rows['position'] ||
                        $tin != $rows['tin'] ||
                        $sss != $rows['sss'] ||
                        $philhealth != $rows['philhealth'] ||
                        $pagibig != $rows['pag_ibig'] ||
                        (isset($_POST['resigned']) || $resignation_date != $rows['resignation_date']) ||
                        (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] === UPLOAD_ERR_OK)
                    )
                    { //changes were made
                        $stmt = $conn->prepare("SELECT * FROM employees WHERE control_number = ?");
                        $stmt->bind_param("s", $control_number);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $stmt->close();
                        $unchanged_msg = '';
                        if ($result->num_rows > 0) {
                            $rows = $result->fetch_assoc();
                            if (
                                $new_control_number != $rows['control_number'] ||
                                $surname != $rows['surname'] ||
                                $name != $rows['name'] ||
                                $middle_name != $rows['middle_name'] ||
                                $birthday != $rows['birthday'] ||
                                $civil_status != $rows['civil_status'] ||
                                $gender != $rows['gender'] ||
                                $employment_status != $rows['employment_status'] ||
                                $classification != $rows['classification'] ||
                                $date_hired != $rows['date_hired'] ||
                                $years_service != $rows['years_in_service'] ||
                                $address != $rows['address'] ||
                                $contact != $rows['contact'] ||
                                $email != $rows['email'] ||
                                $course_taken != $rows['course_taken'] ||
                                $further_studies != $rows['further_studies'] ||
                                $number_units != $rows['number_of_units'] ||
                                $prc_number != $rows['prc_number'] ||
                                $prc_exp != $rows['prc_exp'] ||
                                $position != $rows['position'] ||
                                $tin != $rows['tin'] ||
                                $sss != $rows['sss'] ||
                                $philhealth != $rows['philhealth'] ||
                                $pagibig != $rows['pag_ibig']
                            ){
                                $stmt = $conn->prepare("UPDATE employees SET control_number=?, surname=?, name=?, middle_name=?, birthday=?, civil_status=?, gender=?, employment_status=?, classification=?, date_hired=?, years_in_service=?, address=?, contact=?, email=?, course_taken=?, further_studies=?, number_of_units=?, prc_number=?, prc_exp=?, position=?, tin=?, sss=?, philhealth=?, pag_ibig=? WHERE control_number=?");
                                $stmt->bind_param("ssssssssssissssssssssssss", $new_control_number, $surname, $name, $middle_name, $birthday, $civil_status, $gender, $employment_status, $classification, $date_hired, $years_service, $address, $contact, $email, $course_taken, $further_studies, $number_units, $prc_number, $prc_exp, $position, $tin, $sss, $philhealth, $pagibig, $control_number);
                                $stmt->execute();
                                $success_message = "Information Successfully Updated";
                                $control_number = $new_control_number;
            
                                $type = "Information Updated";
                            }
                        }
                        
                        if (isset($_POST['resignationDate']) && $resignation_date != "") {
                            $resignationDate = $_POST['resignationDate'];
                            
                            if (isset($_POST['resigned'])){
                                $stmt = $conn->prepare("UPDATE employees SET status = 'resigned', resignation_date = ? WHERE control_number = ?");
                                $stmt->bind_param("ss", $resignationDate, $control_number);
                                $stmt->execute();
                                $affectedRows = $stmt->affected_rows;
                                $stmt->close();
                                
                                if ($affectedRows > 0){
                                    if (isset($type)){
                                        $type .= ", Resignation Updated";
                                    }
                                    else{
                                        $type = "Resignation Updated";
                                    }
                                    $success_message = "Information Successfully Updated";
                                }
                            } else {
                                $stmt = $conn->prepare("UPDATE employees SET status = 'resigned', resignation_date = ? WHERE control_number = ? AND resignation_date != ?");
                                $stmt->bind_param("sss", $resignationDate, $control_number, $resignationDate);
                                $stmt->execute();
                                $affectedRows = $stmt->affected_rows;
                                $stmt->close();
                                
                                if ($affectedRows > 0){
                                    if (isset($type)){
                                        $type .= ", Resignation Updated";
                                    }
                                    else{
                                        $type = "Resignation Updated";
                                    }
                                    $success_message = "Information Successfully Updated";
                                }
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
                                        $type .= ", Profile Updated";
                                    }
                                    else{
                                        $type = "Profile Updated";
                                    }
                                }
                                $success_message = "Information Successfully Updated";
                            }
                        }

                        if (isset($type)) {
                            history($_SESSION['control_number'], $control_number, $type);
                            echo '<script>
                                    alert("Information Successfully Updated!");
                                    window.location="edit_info.php?control=' . $control_number . '&success_message=' . urlencode($success_message) . '"
                                </script>';
                            exit;
                        }
                        
                    }
                    else{//no changes were made
                        $unchanged_msg = "There were no changes made";
                    }
                    
                }
            }
        }
    }

    $sql = "SELECT * FROM employees WHERE control_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $control_number);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $emp_status = $row['status'];
        if ($emp_status == 'resigned') {
            $resignation_date = $row['resignation_date'];
        }
        $profile_image = $row['image'];
        $control_number = $row['control_number'];
        $surname = $row['surname'];
        $name = $row['name'];
        $middle_name = $row['middle_name'];
        $birthday = $row['birthday'];
        $civil_status = $row['civil_status'];
        $gender = $row['gender'];
        $employment_status = $row['employment_status'];
        $classification = $row['classification'];
        $date_hired = $row['date_hired'];
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
            margin-top: 0px;
            overflow: auto;
        }

        .right-label {
            float: right;
            clear: right;
        }

        input, select {
            width: 100%;
            height: 40px;
            font-size: 17px;
            box-sizing: border-box;
            padding-left: 8px;
        }
        
        .submission {
            position: relative;
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .submission a {
            padding-top: 13px;
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

        .unchaged-message {
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
        }

        table {
            border-collapse: separate;
            width: 100%;
            font-size: 20px;
            border-spacing: 10px 0;
            margin-bottom: 20px;
        }
        th, td, tr {
            text-align: left;            
        }

        #control_number_th {
            width: 14%;
        }

        #th_2 {
            width: 25%;
        }


        #prc_exp_th {
            width: 12%;
        }

        #thirty {
            width: 25%;
        }

        #resignationDate {
            width: 30%;
        }

        .submit-button {
            display: inline-block;
            padding: 5px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            width: 200px;
            height: 50px;
        }

        #save {
            background-color: #4CAF50;
        }

        #save:hover {
            background-color: #3a8a3d;
        }

        #back {
            background-color: #fc0303;
        }

        #back:hover {
            background-color: #9c0402;
        }

        #resigned {
            width: 20px;
            height: 20px;
            margin-left: 10px;
        }

        .input-container {
            display: relative;
            
        }
        .hidden {
            display: none;
        }

        .preview-box {
            width: 120px;
            height: 120px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .preview-box img {
            display: flex;
            max-width: 120px;
            max-height: 120px;
        }

        #resignation-label {
            font-size: 15px;
        }

        #resignation-col {
            width: 30%;
        }

        #image-upload {
            display: absolute;
            max-width: 120px;
            max-height: 120px;
        }
    </style>
</head>
<body>
    <div class="home-section">
        <div class="home-content">
            <form method="POST" action="" autocomplete="off" enctype="multipart/form-data">
                <h2>Edit Employee Information</h2><br>
                
                <table>
                    <tr>
                        <th><label for="control_number">Control Number:</label></th>
                        <th><label for="surname" id="sur-tab">Surname:</label></th>
                        <th><label for="name" id="name-tab">Name:</label></th>
                        <th><label for="middle_name" id="mid-tab">Middle Name:</label><br></th>
                    </tr>

                    <tr>
                        <th id="control_number_th"><input type="text" name="control_number" id="control_number" value="<?php echo isset($control_number) ? $control_number : '' ?>" required></th>
                        <th><input type="text" name="surname" id="surname" value="<?php echo isset($surname) ? $surname : '' ?>" required></th>
                        <th><input type="text" name="name" id="name" value="<?php echo isset($name) ? $name : '' ?>"></th>
                        <th><input type="text" name="middle_name" id="middle_name" value="<?php echo isset($middle_name) ? $middle_name : '' ?>" ><br></th>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th><label for="birthday">Birthday:</label>
                        <th><label for="civil_status" id="civil-tab">Civil Status:</label>
                        <th><label for="gender" id="gender-tab">Gender:</label>
                        <th><label for="employment_status" id="status-tab">Employment Status:</label> 
                        <th ><label for="classification" id="class-tab">Classification:</label>
                        <th ><label for="date_hired">Date Hired:</label>
                        <th><label for="years_service">Years in Service:</label><br>
                    </tr>

                    <tr>
                        <th><input type="date" name="birthday" id="birthday" value="<?php echo isset($birthday) ? $birthday : '' ?>" >
                        <th>
                            <select name="civil_status" id="civil_status" >
                                <option value="">Civil Status</option>
                                <option value="Single" <?php echo (isset($civil_status) && $civil_status === 'Single') ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo (isset($civil_status) && $civil_status === 'Married') ? 'selected' : ''; ?>>Married</option>
                                <option value="Widowed" <?php echo (isset($civil_status) && $civil_status === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                            </select>
                        </th>
                        <th>
                            <select name="gender" id="gender" >
                                <option value="">Select Gender</option>
                                <option value="Male" <?php echo (isset($gender) && $gender === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo (isset($gender) && $gender === 'Female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </th>
                        <th>
                            <select name="employment_status" id="employment_status" >
                                <option value="">Employment Status</option>
                                <?php 
                                    $data_type = "emp_status";
                                    $sql = "SELECT * FROM data_values WHERE data_type = ?";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->bind_param("s", $data_type);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        echo $row['data_value'];
                                        echo '<option value="' . $row['data_value'] . '" ' . (isset($employment_status) && $employment_status === $row['data_value'] ? 'selected' : '') . '>' . $row['data_value'] . '</option>';
                                    }
                                ?>
                            </select>
                        </th>
                        <th>
                            <select name="classification" id="classification">
                                <option value="">Classification</option>
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
                                    $conn->close();
                                ?>
                            </select>
                        </th>
                        <th><input type="date" name="date_hired" id="date_hired" value="<?php echo isset($date_hired) ? $date_hired : '' ?>" >
                        <th><input type="number" name="years_service" id="years_service" value="<?php echo isset($years_service) ? $years_service : '' ?>"><br>
                    </tr>
                </table>
                
                <table>
                    <tr>
                        <th><label for="address">Address:</label>
                        <th id="th_2"><label for="contact">Contact Number:</label>
                        <th id="th_2"><label for="email">Email:</label><br>
                    </tr>

                    <tr>  
                        <th><input type="address" name="address" id="address" value="<?php echo isset($address) ? $address : '' ?>">
                        <th><input type="text" name="contact" id="contact" value="<?php echo isset($contact) ? $contact : '' ?>" >
                        <th><input type="email" name="email" id="email" value="<?php echo isset($email) ? $email : '' ?>" ><br>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th><label for="course_taken">Course Taken:</label>
                        <th><label for="further_studies">Further Studies:</label>
                        <th><label for="number_units">Number of Units:</label><br>
                    </tr>

                    <tr>
                    <th><input type="text" name="course_taken" id="course_taken" value="<?php echo isset($course_taken) ? $course_taken : '' ?>" >
                    <th><input type="text" name="further_studies" id="further_studies" value="<?php echo isset($further_studies) ? $further_studies : '' ?>">
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
                        <th id="thirty"><input type="text" name="prc_number" id="prc_number" value="<?php echo isset($prc_number) ? $prc_number : '' ?>">
                        <th id="prc_exp_th"><input type="date" name="prc_exp" id="prc_exp" value="<?php echo isset($prc_exp) ? $prc_exp : '' ?>">
                        <th><input type="text" name="position" id="position" value="<?php echo isset($position) ? $position : '' ?>" >
                        <th id="thirty"><input type="text" name="tin" id="tin" value="<?php echo isset($tin) ? $tin : '' ?>"><br>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th><label for="sss">SSS:</label>
                        <th><label for="philhealth">PHILHEALTH:</label>
                        <th><label for="pagibig">PAG-IBIG:</label><br>
                    </tr>

                    <tr>
                        <th><input type="text" name="sss" id="sss" value="<?php echo isset($sss) ? $sss : '' ?>">
                        <th><input type="text" name="philhealth" id="philhealth" value="<?php echo isset($philhealth) ? $philhealth : '' ?>">
                        <th><input type="text" name="pagibig" id="pagibig" value="<?php echo isset($pagibig) ? $pagibig : '' ?>"><br>
                    </tr>
                </table>

                <table>
                    <tr>
                        <?php 
                            if (isset($emp_status) && $emp_status == 'active') {
                                echo '<th id="resignation-col">
                                    <div class="input-container">
                                        <label for="resigned">Resigned</label>
                                        <input type="checkbox" name="resigned" id="resigned" onchange="toggleResignationDate()">

                                        <div id="resignationDateContainer" class="hidden">
                                            <label for="resignationDate" id="resignation-label">Resignation Date:</label>
                                            <input type="date" name="resignationDate" id="resignationDate" value="' . (isset($resignation_date) ? $resignation_date : '') . '">
                                        </div>
                                    </div>
                                </th>';
                            }
                            else {
                                echo '<th>Resignation Date: <input type="date" name="resignationDate" id="resignationDate" value="' . (isset($resignation_date) ? $resignation_date : '') . '"></th>';
                            }
                        ?>

                        <th>Image:
                            <input type="file" name="fileToUpload" id="fileToUpload" onchange="previewImage();">
                            <div class="preview-box" id="previewBox">
                                <?php 
                                    if (isset($success_message) && isset($uniqueFilename)) {
                                        echo '<img src="images/' . $uniqueFilename . '" id="image-upload">';
                                    }
                                ?>
                                <img src="" id="previewImg">
                            </div>
                        </th>

                        <th>
                            <div class="submission">
                                <?php
                                    if (isset($unchanged_msg) && $unchanged_msg != '') {
                                        echo '<div>';
                                        echo '<div class="unchaged-message">' . $unchanged_msg . '</div>';
                                    }
                                    else{
                                        if (isset($success_message)) {
                                            echo '<div>';
                                            if (isset($error_message)) {
                                                unset($error_message);
                                            }
                                            echo '<div class="success-message">' . $success_message . '</div>';
                                        }
                                        if (isset($error_message)) {
                                            echo '<div>';
                                            echo '<div class="error-message">' . $error_message . '</div>';
                                        }
                                    }
                                    echo'<div class="submission" id="buttons">';
                                    if (isset($success_message)) {
                                        echo '<a class="submit-button" id="save" href="edit_info.php?control=' . $control_number . '" id="back">Edit</a>';
                                        echo '<a class="submit-button" href="information.php?control=' . $control_number . '" id="back">Back</a>';
                                    }
                                    else{
                                        echo '<button type="submit" class="submit-button" id="save">Save</button>';
                                        echo '<a class="submit-button" href="information.php?control=' . $control_number . '" id="back">Back</a>';
                                    }
                                    echo'</div>';
                                    echo '</div>';
                                ?>
                            </div>
                        </th>
                    </tr>
                </table>
            </form>
        </div>
    </div>   
</body>

<script>
    function toggleResignationDate() {
      const resignationDateContainer = document.getElementById('resignationDateContainer');
      const resignedCheckbox = document.getElementById('resigned');
      const resignationDateInput = document.getElementById('resignationDate');

      if (resignedCheckbox.checked) {
        resignationDateContainer.classList.remove('hidden');
        resignationDateInput.required = true;
      } else {
        resignationDateContainer.classList.add('hidden');
        resignationDateInput.required = false;
      }
    }

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
        var inputFields = document.querySelectorAll('#control_number, #surname, #name, #middle_name, #birthday, #civil_status, #gender, #employment_status, #classification, #date_hired, #years_service, #address, #contact, #email, #course_taken, #further_studies, #number_units, #prc_number, #prc_exp, #position, #tin, #sss, #philhealth, #pagibig, #fileToUpload, #resigned, #resignationDate');

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
