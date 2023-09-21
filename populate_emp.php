<?php
$conn = new mysqli('localhost', 'root', '', 'hris');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}else{

    // Define arrays for possible values
    $employmentStatusOptions = [
        1 => 'Permanent',
        2 => 'Probationary',
        3 => 'Substitute',
        4 => 'Part-time',
    ];
    
    $classificationOptions = [
        1 => 'Rank and File (Faculty)',
        2 => 'Rank and File (Staff)',
        3 => 'Academic Middle-Level Administrator',
        4 => 'Non-Academic Middle-Level Administrator',
        5 => 'Top Management',
        6 => 'Auxiliary',
        7 => 'Religious of the Assumption',
    ];
    
    $departmentOptions = [
        1 => 'Pre-School',
        2 => 'Araling Panlipunan',
        3 => 'Christian Living Education',
        4 => 'Com. Arts English',
        5 => 'Com. Arts Filipino',
        6 => 'Mathematics',
        7 => 'Music Arts Physical Education and Health',
        8 => 'Science',
        9 => 'Technology and Livelihood',
        10 => 'Core Group',
        11 => 'Non - Teaching Staff',
        12 => 'Auxilliary',
        13 => 'Religious of the Assumption',
    ];
    
    $suffixOptions = [
        1 => 'Jr.',
        2 => 'Sr.',
        3 => 'III',
        4 => 'II',
        5 => '',
        6 => '',
        7 => '',
        8 => '',
        9 => '',
        10 => '',
        11 => '',
        12 => ''
    ];

    // Function to generate a random value from an array
    function getRandomValue($array) {
        return $array[array_rand($array)];
    }

    // Database connection code here

    // Loop to generate 50 random data entries
    for ($i = 1; $i <= 50; $i++) {
        do {
            $controlNumber = mt_rand(10, 99) . "-" . mt_rand(10, 99) . mt_rand(10, 99);
            $sql = "SELECT * FROM employees WHERE control_number = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $controlNumber);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } while ($result->num_rows > 0);
        
        // Generate a unique control number, you may need to adjust this logic
        $surname = "Surname" . $i;
        $name = "Name" . $i;
        $middleName = rand(0, 1) ? "MiddleName" . $i : "";
        $suffix = getRandomValue($suffixOptions);
        $birthday = date('Y-m-d', mt_rand(1, time()));
        $age = mt_rand(18, 65);
        $civilStatus = getRandomValue(['Single', 'Married', 'Widowed']);
        $gender = getRandomValue(['Male', 'Female']);
        $employmentStatus = getRandomValue($employmentStatusOptions);
        $classification = getRandomValue($classificationOptions);
        $dateHired = date('Y-m-d', mt_rand(1, time()));
        $yearsInService = mt_rand(0, 30);
        $address = "Address" . $i;
        $contact = "Contact" . $i;
        $email = "email" . $i . "@example.com";
        $courseTaken = "Course" . $i;
        $furtherStudies = rand(0, 1) ? "Studies" . $i : "";
        $numberOfUnits = mt_rand(0, 200);
        $prcNumber = "PRC" . $i;
        $prcExp = date('Y-m-d', mt_rand(1, time()));
        $position = "Position" . $i;
        $tin = rand(0, 1) ? "TIN" . $i : "";
        $sss = rand(0, 1) ? "SSS" . $i : "";
        $philhealth = rand(0, 1) ? "Philhealth" . $i : "";
        $pagIbig = rand(0, 1) ? "PagIbig" . $i : "";
        $status = getRandomValue(['active', 'resigned', 'active', 'active']);
        $image = "default.jpg";
        $resignationDate = date('Y-m-d', mt_rand(1, time()));
        $department = getRandomValue($departmentOptions);
        $vl = rand(0, 1) ? (rand(0, 1) ? 10 : 0) : 5;
        $sl = rand(0, 1) ? (rand(0, 1) ? 10 : 0) : 5;
        $remainingLeave = $vl + $sl;
        $lessYis = mt_rand(0, 5);
        
        // Prepare the SQL INSERT statement
        $sql = "INSERT INTO `employees`(`control_number`, `surname`, `name`, `middle_name`, `suffix`, `birthday`, `age`, `civil_status`, `gender`, `employment_status`, `classification`, `date_hired`, `years_in_service`, `address`, `contact`, `email`, `course_taken`, `further_studies`, `number_of_units`, `prc_number`, `prc_exp`, `position`, `tin`, `sss`, `philhealth`, `pag_ibig`, `status`, `image`, `resignation_date`, `department`, `vl`, `sl`, `remaining_leave`, `less_yis`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the SQL statement for user table
        $sql_user = "INSERT INTO `user`(`control_number`, `surname`, `name`, `middle_name`, `suffix`, `username`, `password`, `access_level`, `status`, `image`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        $stmt_user = $conn->prepare($sql_user);

        $stmt->bind_param("ssssssisssssisssssssssssssssssiiii", $controlNumber, $surname, $name, $middleName, $suffix, $birthday, $age, $civilStatus, $gender, $employmentStatus, $classification, $dateHired, $yearsInService, $address, $contact, $email, $courseTaken, $furtherStudies, $numberOfUnits, $prcNumber, $prcExp, $position, $tin, $sss, $philhealth, $pagIbig, $status, $image, $resignationDate, $department, $vl, $sl, $remainingLeave, $lessYis);
        $stmt->execute();

        // Generate username and password and bind parameters for user table
        $username = sha1($controlNumber);
        $password = sha1($birthday);
        $access = "employee";
        if ($status != "resigned"){
            $status_user = "active";
        }else{
            $status_user = "disabled";
        }
        
        $stmt_user->bind_param("ssssssssss", $controlNumber, $surname, $name, $middleName, $suffix, $username, $password, $access, $status_user, $image);
        $stmt_user->execute();

        echo "Record $i inserted successfully.<br>";
        

        // Close the prepared statements
        $stmt->close();
        $stmt_user->close();
        }
}
        // Close the database connection
        $conn->close();
?>