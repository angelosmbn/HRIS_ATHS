<?php
// Include your database connection code here

if (isset($_GET['school_year'])) {
    $selectedSchoolYear = $_GET['school_year'];
    
    // Retrieve months for the selected school year from the database
    $sql = "SELECT months FROM attendance_year WHERE school_year = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selectedSchoolYear);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    // Convert months string to an array
    $months = explode(", ", $row['months']);
    
    // Return months as a JSON response
    header('Content-Type: application/json');
    echo json_encode($months);
}
?>
