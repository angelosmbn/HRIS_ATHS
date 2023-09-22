<?php
    session_start();

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
</style>
</head>
<body>
    <form action="" method="POST">
        <h1>
            <span style="color: black;">Employees Request</span>
        </h1>

        <table>
            <thead>
                <tr class="fixed-row">
                    <th>#</th>
                    <th>Name</th>
                    <th>Requested On</th>
                    <th>Needed On</th>
                    <th>Request</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <?php
                $sql = "SELECT * FROM request WHERE status = 'pending'";
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
                        echo "<td id='action'>";
                        echo "<button class='accept-button' data-request-id='" . $row['request_id'] . "' data-control-number='" . $row['control_number'] . "'>Accept</button>";
                        echo " | ";
                        echo "<button class='decline-button' data-request-id='" . $row['request_id'] . "' data-control-number='" . $row['control_number'] . "'>Decline</button>";
                        echo "</td>";
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

    // Function to send an AJAX request to update the status
    function updateStatus(requestId, status, controlNumber) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "request_update.php", true); // Replace "update_status.php" with the actual server endpoint
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Update the row in the table or perform any other necessary actions
                    const row = document.querySelector(`tr[data-row-id='row-${requestId}']`);
                    if (status === 'accept') {
                        // Update the status and process_date in the table
                        row.querySelector(".status").textContent = "Accept";
                        row.querySelector(".process-date").textContent = new Date().toLocaleString(); // Set current timestamp
                    } else if (status === 'decline') {
                        // Update the status and process_date in the table
                        row.querySelector(".status").textContent = "Decline";
                        row.querySelector(".process-date").textContent = new Date().toLocaleString(); // Set current timestamp
                    }
                } else {
                    // Handle the error here
                    console.error("Failed to update status.");
                }
            }
        };
        
        xhr.send(`request_id=${requestId}&status=${status}&control_number=${controlNumber}`);
    }

    // Function to display a confirmation dialog
    function confirmAction(requestId, status, controlNumber) {
        const confirmation = confirm(`Are you sure you want to ${status} this request?`);

        if (confirmation) {
            // User confirmed, proceed with the action
            updateStatus(requestId, status, controlNumber);
        } else {
            // User canceled, do nothing
        }
    }
    
    // Add event listener to Accept and Decline buttons
    const acceptButtons = document.querySelectorAll(".accept-button");
    const declineButtons = document.querySelectorAll(".decline-button");

    acceptButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Get the request ID, status, and control number associated with the clicked button
            const requestId = this.getAttribute("data-request-id");
            const controlNumber = this.getAttribute("data-control-number");
            // Display a confirmation dialog before accepting
            confirmAction(requestId, "accept", controlNumber);
        });
    });

    declineButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Get the request ID, status, and control number associated with the clicked button
            const requestId = this.getAttribute("data-request-id");
            const controlNumber = this.getAttribute("data-control-number");
            // Display a confirmation dialog before declining
            confirmAction(requestId, "decline", controlNumber);
        });
    });
</script>
</html>

