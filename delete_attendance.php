<?php
session_start();

if (!isset($_SESSION['user'])) {
    // Redirect to the admin dashboard page
    header("Location: login_hris.php");
    exit();
}

include 'navbar_hris.php';
change_default();

if ($_SESSION['access_level'] == 'super admin') {

    $sql = "SELECT * FROM attendance";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);

    if ($resultCheck == 0) {
        echo '<script>
        alert("There are no records to be deleted.");
        window.location="settings.php";
        </script>';
        exit;

    }
    else{
        $sql = "DELETE FROM attendance";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        history($_SESSION['control_number'], "Settings", "Deleted Attendance Record");

        echo '<script>
            alert("Successfully Deleted Attendance Record.");
            window.location="settings.php";
            </script>';
        exit;
    }
} else {
    echo '<script>
        alert("Invalid Access Level.");
        window.location="Admin_home_hris.php";
        </script>';
    exit;
}
?>
