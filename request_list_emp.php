<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
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
        width: 70%;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        margin: auto; /* Center horizontally */
        border: 0.5px solid black;
        max-height: 700px;
        overflow: auto;
    }

    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 14px;
    }

    th, td {
        display: relative;
        text-align: left;
        padding: 8px;
        border: 1px solid black;
        white-space: nowrap;
        height: 0px;
        vertical-align: top;
    }

    th {
        background: bisque;
    }

    .highlighted-row {
        background-color: #5cabff;
    }

    td #click:hover {
        cursor: pointer;
    }

    .fixed-row {
        position: sticky;
        top: 0;
        background-color: #f0f0f0;
        z-index: 0; /* Make sure the fixed row appears above other content */
    }

    form .fixed-row {
        padding: 1rem;
    }

    label {
        font-weight: bold;
    }
    #action{
        text-align: center;
    }
    button{
        width: 70px;
        height: 25px;
        border-radius: 5px;
        border: none;
        color: white;
        font-size: 14px;
    }
    .accept-button {
        background-color: #4CAF50;
    }
    .accept-button:hover {
        background-color: #3a8a3d;
    }
    .decline-button {
        background-color: #f44336;
    }
    .decline-button:hover {
        background-color: #9c0402;
    }
    select {
        width: 10%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid black;
        border-radius: 3px;
        margin-bottom: 20px;
        float: right;
        clear: right;
    }
</style>
</head>
<body>
    <form action="" method="POST">
        <h1>
            <span style="color: black;">My Request</span>
            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?php echo isset($_POST['status']) && $_POST['status'] === 'all' ? 'selected' : ''; ?>>All</option>
                <option value="pending" <?php echo isset($_POST['status']) && $_POST['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                <option value="accept" <?php echo isset($_POST['status']) && $_POST['status'] === 'accept' ? 'selected' : ''; ?>>Accept</option>
                <option value="decline" <?php echo isset($_POST['status']) && $_POST['status'] === 'decline' ? 'selected' : ''; ?>>Decline</option>
            </select>
        </h1>
        <div></div>
        <table>
            <thead>
                <tr class="fixed-row">
                    <th>#</th>
                    <th>Name</th>
                    <th>Requested On</th>
                    <th>Needed On</th>
                    <th>Request</th>
                    <th>Description</th>
                    <th>Status</th>
                </tr>
            </thead>

            <?php
                $statusFilter = isset($_POST['status']) && $_POST['status'] !== 'all' ? $_POST['status'] : '';

                // Modify the SQL query to include the status filter
                $sql = "SELECT * FROM request WHERE control_number = '" . $_SESSION['control_number'] . "'";
                if (!empty($statusFilter)) {
                    $sql .= " AND status = '$statusFilter'";
                }
                $sql .= " ORDER BY CASE WHEN status = 'pending' THEN 0 ELSE 1 END, request_date DESC";

                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $i . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . date('M j, Y \a\t g:i a', strtotime($row['request_date'])) . "</td>";
                        echo "<td>" . date('M j, Y', strtotime($row['date_needed'])) . "</td>";
                        echo "<td>" . wordwrap($row['req'], 50, "<br>\n", true) . "</td>";
                        echo "<td>" . wordwrap($row['description'], 50, "<br>\n", true) . "</td>";
                        $status = strtolower($row['status']); // Convert the status to lowercase for comparison
                        echo "<td style='color: " . ($status === 'pending' ? 'black' : ($status === 'accept' ? 'green' : ($status === 'decline' ? 'red' : ''))) . ";'>" . ucfirst($row['status']) . "</td>";
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='7'>No request found.</td></tr>";
                }
            ?>
        </table>
    </form>
</body>

<script>
    // Add event listener to each row in the table
    document.addEventListener("DOMContentLoaded", function () {
        const rows = document.querySelectorAll("tr[data-row-id]");

        rows.forEach(row => {
            row.addEventListener("dblclick", function () {
                if (this.classList.contains("highlighted-row")) {
                    // If the row is already highlighted, remove the highlight
                    this.classList.remove("highlighted-row");
                } else {
                    // Remove the 'highlighted-row' class from all rows
                    rows.forEach(row => row.classList.remove("highlighted-row"));

                    // Add the 'highlighted-row' class to the clicked row
                    this.classList.add("highlighted-row");
                }
            });
        });
    });

</script>
</html>

