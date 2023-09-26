
<?php
session_start();
function recent_request($admin_id, $employee_id, $type, $requestId) {
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');

    //Get Admin Name
    $get_admin_name = $conn->prepare("SELECT * FROM user WHERE control_number = ?");
    $get_admin_name->bind_param("s", $admin_id);
    $get_admin_name->execute();
    $result_admin_name = $get_admin_name->get_result();
    if ($result_admin_name->num_rows > 0) {
      $admin = $result_admin_name->fetch_assoc();
      $admin_name = $admin['surname'] . ", " . $admin['name'];
      if ($admin['middle_name'] !== "") {
        $admin_name .= " " . $admin['middle_name'][0] . ".";
      }
      if ($admin['suffix'] !== "") {
        $admin_name .= " " . $admin['suffix'];
      }
    }else{
      $admin_name = "Unknown";
    }

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

    $getReq = $conn->prepare("SELECT * FROM request WHERE request_id = ?");
    $getReq->bind_param("i", $requestId);
    $getReq->execute();
    $result_req = $getReq->get_result();
    if ($result_req->num_rows > 0){
      $req = $result_req->fetch_assoc();
      $request_name  = $req['req'];
    }else{
      $request_name="";
    }
    
    $stmt = $conn->prepare("INSERT INTO recent_request (admin_id, employee_id, type, admin_name, employee_name, req)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $admin_id, $employee_id, $type, $admin_name, $employee_name, $request_name);
    $stmt->execute();
    $stmt->close();
  }
// Check if request_id and status are set in the POST request
if (isset($_POST['request_id']) && isset($_POST['status']) && isset($_POST['control_number'])) {
    // Retrieve the values
    $employee_id = $_POST['control_number'];
    $requestId = $_POST['request_id'];
    $status = $_POST['status'];
    $timestamp = date('Y-m-d H:i:s');
    // Now you can perform the necessary database update based on $requestId and $status
    // For example, you can use prepared statements to update the database
    
    // Establish a database connection (replace with your database connection code)
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');

    // Check if the connection was successful
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Update the database based on $requestId and $status
    $sql = "UPDATE request SET status = ?, process_date = ? WHERE request_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "sss", $status, $timestamp, $requestId);
    
    // Execute the statement
    if (mysqli_stmt_execute($stmt)) {
        // The database update was successful
        echo "Status updated successfully";
        // Add to recent request
        $type = ucfirst($status) . " Request";
        recent_request($_SESSION['control_number'], $employee_id, $type, $requestId);
    } else {
        // Handle the error if the update fails
        echo "Error updating status: " . mysqli_error($conn);
    }

    // Close the statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Handle the case where request_id or status is not set in the POST request
    echo "Invalid request";
}
?>
