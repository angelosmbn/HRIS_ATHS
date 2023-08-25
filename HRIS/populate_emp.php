<?php
$conn = new mysqli('localhost', 'root', '', 'hris');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

for ($control_number = 3; $control_number <= 3; $control_number++) {
    $sql = "INSERT INTO `employees` (`control_number`, `surname`, `name`, `middle_name`, `birthday`, `civil_status`, `gender`, `employment_status`, `classification`, `date_hired`, `years_in_service`, `address`, `contact`, `email`, `course_taken`, `further_studies`, `number_of_units`, `prc_number`, `prc_exp`, `position`, `tin`, `sss`, `philhealth`, `pag_ibig`, `status`, `image`, `resignation_date`)
    VALUES ('$control_number', 'One', 'One', 'One', '2023-08-09', 'Married', 'Male', 'Permanent', 'Rank and File', '2002-09-16', '20', 'Lot 1, Block 1, Phase 2, La Trevi Estate,Santa Monica', '09271340561', 'angelosimbulan16@gmail.com', 'One', 'One', '1', 'One', '2023-08-21', 'One', 'One', 'One', 'One', 'One', 'active', '1692915156_357644885_592683516315809_7523086810272773581_n.jpg', NULL)";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully for control_number: $control_number<br>";
    } else {
        echo "Error inserting record for control_number: $control_number - " . $conn->error . "<br>";
    }
}

$conn->close();
?>