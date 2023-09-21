<?php
    session_start();

    if (!isset($_SESSION['user'])) {
        // Redirect to the admin dashboard page
        header("Location: login_hris.php");
        exit();
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
        width: 50%;
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

    #recordInputs {
        display: none;
    }

    #information {
        width: 100%;
        resize: vertical;
        min-height: 100px;
        margin-top: 10px;
    }

    #recordInputs input[type="text"] {
        width: 100%;
        margin-top: 10px;
        padding: 5px;
        border: 1px solid black;
        border-radius: 3px;
    }

    #recordInputs button, #add_record, #back {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 20px;
    }

    #add_record, #back {
        background-color: #007bff;
        margin-bottom: 10px;
    }

    #back {
        background-color: #fc0303;
        float: right;
        clear: right;
    }

    #back:hover {
        background-color: #9c0402;
    }

    #add_record:hover {
        background-color: #0056b3;
    }

    #submitRecord:hover {
        background-color: #3a8a3d;
    }

    a {
        text-decoration: none;
        color: #000;
    }

    a:hover {
        color: #0056b3;
        font-weight: bold;
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
</style>
</head>
<body>
    

    <form action="" method="POST">
        <h1>
            <span style="color: black;">Department Values</span>  
            <span><button type="button" id="back" onclick="redirectToSettings()">Back</button></span>
        </h1>
        <div>
        <button type="button" id="add_record" onclick="toggleInputs()">Add Record</button>
        </div>
        <div id="recordInputs" style="display: none;">
            <label for="value">Enter Department Value:</label>
            <input type="text" name="value" id="value" placeholder="Enter Value" required><br><br>
            <button type="submit" name="submitRecord" id="submitRecord">Save</button>
        </div>

        <table>
            <thead>
                <tr class="fixed-row">
                    <th>#</th>
                    <th>Department</th>
                </tr>
            </thead>

            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitRecord'])) {
                    $value = $_POST['value'];
                    $type = "department";
                    $stmt = $conn->prepare("INSERT INTO data_values (data_type, data_value) VALUES (?, ?)");
                    $stmt->bind_param("ss", $type, $value);
                    $result = $stmt->execute();  // Execute the statement and store the result

                    if ($result) {
                        // Redirect to prevent multiple insertions
                        history($_SESSION['control_number'], "Settings", "Department Value Added");
                        echo '<script>window.location="settings_department_values.php"</script>';
                        exit();
                    } else {
                        echo "Error: " . $stmt->error; // Use $stmt->error instead of mysqli_error($conn)
                    }
                }

                $sql = "SELECT * FROM data_values WHERE data_type = 'department'";
                $result = mysqli_query($conn, $sql);
                $resultCheck = mysqli_num_rows($result);
                if ($resultCheck > 0) {
                    $i = 1;
                    echo '<tbody>';
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr data-row-id='row-$i'>";
                        echo "<td>" . $i . "</td>";
                        if ($i > 13) {
                            echo "<td><a href='settings_edit_status.php?value_id=" . $row['value_id'] . "'>" . $row['data_value'] . "</a></td>";
                        } else {
                            echo "<td>" . $row['data_value'] . "</td>";
                        }
                        echo "</tr>";
                        $i++;
                    }
                    echo '</tbody>';
                } else {
                    echo "<tr><td colspan='3'>No records found.</td></tr>";
                }
            ?>
        </table>
    </form>
</body>

<script>
    function toggleInputs() {
        var inputs = document.getElementById("recordInputs");
        inputs.style.display = (inputs.style.display === "none") ? "block" : "none";
    }

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

    function redirectToSettings() {
        <?php
            echo "window.location.href = 'settings.php';";
        ?>
    }
</script>

</html>
