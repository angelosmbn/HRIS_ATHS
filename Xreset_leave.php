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

    $sql = "SELECT * FROM employees WHERE status = 'active'";
    $result = $conn->query($sql);

    $updateSql = "UPDATE employees SET remaining_leave = ? WHERE control_number = ?";
    $updateStmt = $conn->prepare($updateSql);

    while ($row = mysqli_fetch_assoc($result)) {
        $control_number = $row['control_number'];
        $sl = 0;
        $vl = 0;
        $remainingLeave = 0;
        if ($row['classification'] == 'Rank and File (Faculty)' && $row['employment_status'] == 'Permanent') {
            $sl = 10;
        }
        elseif ($row['classification'] == 'Rank and File (Faculty)' && $row['employment_status'] == 'Probationary') {
            $sl = 5;
        }
        elseif ($row['classification'] == 'Rank and File (Staff)' && $row['employment_status'] == 'Permanent') {
            $sl = 10;
            $vl = 5;
        }
        elseif ($row['classification'] == 'Rank and File (Staff)' && $row['employment_status'] == 'Probationary') {
            $sl = 5;
        }
        elseif (
            $row['classification'] == 'Academic Middle-Level Administrator' ||
            $row['classification'] == 'Non-Academic Middle-Level Administrator' ||
            $row['classification'] == 'Top Management') 
        {
            $sl = 10;
            $vl = 10;
        }
        elseif ($row['classification'] == 'Auxiliary' && $row['employment_status'] == 'Permanent') {
            $sl = 10;
            $vl = 5;
        }
        elseif ($row['classification'] == 'Auxiliary' && $row['employment_status'] == 'Probationary') {
            $sl = 5;
        }
        $remainingLeave = $sl + $vl;
        $updateStmt->bind_param("ds", $remainingLeave, $control_number);
        $updateStmt->execute();
    }

    history($_SESSION['control_number'], "Settings", "Reset Leave Credits");

    echo '<script>
        alert("Successfully Reset Leave Credits.");
        window.location="settings.php";
        </script>';
    exit;

} else {
    echo '<script>
        alert("Successfully Reset Leave Credits.");
        window.location="Admin_home_hris.php";
        </script>';
    exit;
}
?>
