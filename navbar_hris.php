<?php 
if (!isset($_SESSION['user'])) {
  // Redirect to the admin dashboard page
  header("Location: login_hris.php");
  exit();
}
define('MY_TIMEZONE', 'Asia/Manila');
$timezone = new DateTimeZone(MY_TIMEZONE);

if ($_SESSION['access_level'] == 'employee') {
  $inactive_timeout = 300; // 5mins
  if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $inactive_timeout)) {
      session_unset(); // Unset all session variables
      session_destroy(); // Destroy the session
      setcookie("user_id", "", time() - 3600, "/"); // Remove the user ID cookie
      setcookie("user_token", "", time() - 3600, "/"); // Remove the user token cookie
      header("Location: login_hris.php"); // Redirect to the login page
      exit();
  }

  // Update the user's last activity timestamp
  $_SESSION['last_activity'] = time();
}
?>

<?php 
   $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');
   // Logout logic
   if (isset($_GET['logout'])) {
    session_destroy();
    echo ("<script>location.href='login_hris.php'</script>");
    exit();
  }

  function history($admin_id, $employee_id, $type) {
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

    $stmt = $conn->prepare("INSERT INTO history (admin_id, employee_id, type, admin_name, employee_name)
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $admin_id, $employee_id, $type, $admin_name, $employee_name);
    $stmt->execute();
    $stmt->close();
  }

  function change_default() {
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');
    if (true) {
      $sql = "SELECT * FROM user WHERE control_number = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $_SESSION['control_number']);
      $stmt->execute();
      $result_user = $stmt->get_result();
      $stmt->close();

      if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $username = $user['username'];
        $password = $user['password'];

        $sql = "SELECT * FROM employees WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['control_number']);
        $stmt->execute();
        $result_emp = $stmt->get_result();
        $stmt->close();

        if ($result_emp->num_rows > 0) {
          $emp = $result_emp->fetch_assoc();
          $control_number_emp = $emp['control_number'];
          $birthday = $emp['birthday'];
        }

        if ($username == sha1($control_number_emp) ) {
          echo '<script>
                  window.location="settings_change_username.php?control=' . $_SESSION['control_number'] . '"
              </script>';
          exit();
        }
        if ($password == sha1($birthday) ) {
          echo '<script>
                  window.location="settings_change_password.php?control=' . $_SESSION['control_number'] . '"
              </script>';
          exit();
        }
      }

    }
  }

  function check_default() {
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');

      $sql = "SELECT * FROM user WHERE control_number = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $_SESSION['control_number']);
      $stmt->execute();
      $result_user = $stmt->get_result();
      $stmt->close();

      if ($result_user->num_rows > 0) {
        $user = $result_user->fetch_assoc();
        $username = $user['username'];
        $password = $user['password'];

        $sql = "SELECT * FROM employees WHERE control_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $_SESSION['control_number']);
        $stmt->execute();
        $result_emp = $stmt->get_result();
        $stmt->close();

        if ($result_emp->num_rows > 0) {
          $emp = $result_emp->fetch_assoc();
          $control_number_emp = $emp['control_number'];
          $birthday = $emp['birthday'];
        }

        if ($username == sha1($control_number_emp) ) {
          return 1;
        }
        elseif ($password == sha1($birthday) ) {
          return 2;
        }
        else{
          return 0;
        }
      }

    
  }

  function updateSLVL($control_number, $employment_status, $classification) {
    $conn = new mysqli('localhost', 'root', 'assumpta_hris', 'hris');
    $sl = 0;
    $vl = 0;
    
    if ($classification == 'Rank and File (Faculty)' && $employment_status == 'Permanent') {
      $sl = 10;
    }
    elseif ($classification == 'Rank and File (Faculty)' && $employment_status == 'Probationary') {
      $sl = 5;
    }
    elseif ($classification == 'Rank and File (Staff)' && $employment_status == 'Permanent') {
      $sl = 10;
      $vl = 5;
    }
    elseif ($classification == 'Rank and File (Staff)' && $employment_status == 'Probationary') {
      $sl = 5;
    }
    elseif (
      $classification == 'Academic Middle-Level Administrator' ||
      $classification == 'Non-Academic Middle-Level Administrator' ||
      $classification == 'Top Management') 
    {
      $sl = 10;
      $vl = 10;
    }
    elseif ($classification == 'Auxiliary' && $employment_status == 'Permanent') {
      $sl = 10;
      $vl = 5;
    }
    elseif ($classification == 'Auxiliary' && $employment_status == 'Probationary') {
      $sl = 5;
    }

    $remaining_leave = $sl+$vl;
    $sql = "UPDATE employees SET sl=?, vl=?, remaining_leave=? WHERE control_number=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iids", $sl, $vl, $remaining_leave, $control_number);
    $stmt->execute();
  }

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <style>
        /* Google Fonts Import Link */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
        display: relative;
        z-index: 1;
        }
        .sidebar{
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 260px;
        background: #11101d;
        z-index: 100;
        transition: all 0.5s ease;
        }
        .sidebar.close{
        width: 78px;
        }
        .sidebar .logo-details{
        height: 60px;
        width: 100%;
        display: flex;
        align-items: center;
        margin-bottom: 50px;
        }
        .sidebar.close .logo-details{
        margin-bottom: 0px;
        }
        .sidebar .logo-details i{
        font-size: 30px;
        color: #fff;
        height: 50px;
        min-width: 78px;
        text-align: center;
        line-height: 50px;
        }
        .sidebar .logo-details .logo_name{
        margin-top: 15px;
        font-size: 20px;
        color: #fff;
        font-weight: 600;
        transition: 0.3s ease;
        transition-delay: 0.1s;
        }
        .sidebar .logo-details .logout{
        margin-top: 15px;
        font-size: 20px;
        color: #fff;
        font-weight: 600;
        transition: 0.3s ease;
        transition-delay: 0.1s;
        }
        .sidebar.close .logout{
          transition-delay: 0s;
          opacity: 0;
          pointer-events: none;
        }
        .sidebar.close .logo-details .logo_name{
        transition-delay: 0s;
        opacity: 0;
        pointer-events: none;
        }
        .sidebar .nav-links{
        height: 100%;
        padding: 30px 0 150px 0;
        overflow: auto;
        }
        .sidebar.close .nav-links{
        overflow: visible;
        }
        .sidebar .nav-links::-webkit-scrollbar{
        display: none;
        }
        .sidebar .nav-links li{
        position: relative;
        list-style: none;
        transition: all 0.4s ease;
        }
        .sidebar .nav-links li:hover{
        background: #1d1b31;
        }
        .sidebar .nav-links li .iocn-link{
        display: flex;
        align-items: center;
        justify-content: space-between;
        }
        .sidebar.close .nav-links li .iocn-link{
        display: block
        }
        .sidebar .nav-links li i{
        height: 50px;
        min-width: 78px;
        text-align: center;
        line-height: 50px;
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        }
        .sidebar .nav-links li.showMenu i.arrow{
        transform: rotate(-180deg);
        }
        .sidebar.close .nav-links i.arrow{
        display: none;
        }
        .sidebar .nav-links li a{
        display: flex;
        align-items: center;
        text-decoration: none;
        }
        .sidebar .nav-links li a .link_name{
        font-size: 18px;
        font-weight: 400;
        color: #fff;
        transition: all 0.4s ease;
        }
        .sidebar.close .nav-links li a .link_name{
        opacity: 0;
        pointer-events: none;
        }
        .sidebar .nav-links li .sub-menu{
        padding: 6px 6px 14px 80px;
        margin-top: -10px;
        background: #1d1b31;
        display: none;
        }
        .sidebar .nav-links li.showMenu .sub-menu{
        display: block;
        }
        .sidebar .nav-links li .sub-menu a{
        color: #fff;
        font-size: 15px;
        padding: 5px 0;
        white-space: nowrap;
        opacity: 0.6;
        transition: all 0.3s ease;
        }
        .sidebar .nav-links li .sub-menu a:hover{
        opacity: 1;
        }
        .sidebar.close .nav-links li .sub-menu{
        position: absolute;
        left: 100%;
        top: -10px;
        margin-top: 0;
        padding: 10px 20px;
        border-radius: 0 6px 6px 0;
        opacity: 0;
        display: block;
        pointer-events: none;
        transition: 0s;
        }
        .sidebar.close .nav-links li:hover .sub-menu{
        top: 0;
        opacity: 1;
        pointer-events: auto;
        transition: all 0.4s ease;
        }
        .sidebar .nav-links li .sub-menu .link_name{
        display: none;
        }
        .sidebar.close .nav-links li .sub-menu .link_name{
        font-size: 18px;
        opacity: 1;
        display: block;
        }
        .sidebar .nav-links li .sub-menu.blank{
        opacity: 1;
        pointer-events: auto;
        padding: 3px 20px 6px 16px;
        opacity: 0;
        pointer-events: none;
        }
        .sidebar .nav-links li:hover .sub-menu.blank{
        top: 50%;
        transform: translateY(-50%);
        }
        .sidebar .profile-details{
        position: fixed;
        bottom: 0;
        width: 260px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #1d1b31;
        padding: 12px 0;
        transition: all 0.5s ease;
        }
        .sidebar.close .profile-details{
        background: none;
        }
        .sidebar.close .profile-details{
        width: 78px;
        }
        .sidebar .profile-details .profile-content{
        display: flex;
        align-items: center;
        }
        .sidebar .profile-details img{
        height: 52px;
        width: 52px;
        object-fit: cover;
        border-radius: 16px;
        margin: 0 14px 0 12px;
        background: #1d1b31;
        transition: all 0.5s ease;
        }
        .sidebar.close .profile-details img{
        padding: 10px;
        }
        .sidebar .profile-details .profile_name,
        .sidebar .profile-details .job{
        color: #fff;
        font-size: 18px;
        font-weight: 500;
        white-space: nowrap;
        }
        .sidebar.close .profile-details .log-out,
        .sidebar.close .profile-details .profile_name,
        .sidebar.close .profile-details .job{
        display: none;
        }
        .sidebar .profile-details .job{
        font-size: 12px;
        }
        .home-section{
        position: relative;
        left: 260px;
        width: calc(100% - 260px);
        transition: all 0.5s ease;
        }
        .sidebar.close ~ .home-section{
        left: 78px;
        width: calc(100% - 78px);
        }
        .home-section .home-content{
        height: 60px;
        display: relative;
        align-items: center;
        }
        .home-section .home-content .bx-menu,
        .home-section .home-content .text{
        color: #11101d;
        font-size: 35px;
        }
        .home-section .home-content .bx-menu{
        margin: 0 15px;
        cursor: pointer;
        margin-top: 20px;
        }
        .home-section .home-content .text{
        font-size: 26px;
        font-weight: 600;
        }
        @media (max-width: 400px) {
        .sidebar.close .nav-links li .sub-menu{
            display: none;
        }
        .sidebar{
            width: 78px;
        }
        .sidebar.close{
            width: 0;
        }
        .home-section{
            left: 78px;
            width: calc(100% - 78px);
            z-index: 100;
        }
        .sidebar.close ~ .home-section{
            width: 100%;
            left: 0;
        }
        }

        .profile-logo{
            margin-top: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }
        .button2{
          position: absolute;
          width: 50px;
          height: 50px;
          border-radius: 50%;
          cursor: pointer;
        }
        .sidebar .nav-links li.showMenu i.arrow{
        transform: rotate(-180deg);
        }
        .sidebar.close .nav-links i.arrow{
        display: none;
        }
        body{
          background-color: gainsboro;
        }
     </style>
   </head>

<body>
  <div class="sidebar close">
    <div class="logo-details">
      <i class='bx bx-menu-menu'><img class='profile-logo' src="images/aths-logo.png" alt="profileImg"></i>
      <span class="logo_name">
        Welcome to HRIS
      </span>
    </div>

    <ul class="nav-links">
      <?php 
        if ($_SESSION['access_level'] != 'employee') {
      ?>
    <li>
        <a href="Admin_home_hris.php">
          <!--<i class='bx bx-grid-alt' ></i>-->
          <i class="fa-solid fa-border-all" style="color: #d6d6d6;"></i>
          <span class="link_name">Dashboard</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="#">Dashboard</a></li>
        </ul>
      </li>
      <li>
        <div class="iocn-link">
          <a href="cur_emp.php">
            <!--<i class='bx bx-user' ></i>-->
            <!--<i class="fa-regular fa-user fa-2xs" style="color: #dedede;"></i>-->
            <i class="fa-solid fa-users" style="color: #dedede;"></i>
            <span class="link_name">Employees</span>
          </a>
          <!--<i class='bx bxs-chevron-down arrow' ></i>-->
          <i class="fa-solid fa-chevron-down fa-2xs arrow" style="color: #dedede;"></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="cur_emp.php">Employees</a></li>
          <li><a href="celebrants.php">Birthday Celebrants</a></li>
          <li><a href="resigned.php">Resigned Employees</a></li>
          <li><a href="add_emp.php">Add Employees</a></li>
        </ul>
      </li>
      <li>
        <a href="attendance.php">
          <!--<i class='bx bx-folder' ></i>-->
          <!--<i class="fa-solid fa-clipboard-user fa-2xs" style="color: #dedede;"></i>-->
          <i class="fa-solid fa-calendar-days" style="color: #dedede;"></i>
          <span class="link_name">Attendance</span>
        </a>
        <ul class="sub-menu">
          <li><a class="link_name" href="attendance.php">Attendance</a></li>
          <li><a href="add_school_year.php">Add School Year</a></li>
        </ul>
      </li>
      <!--<li>
        <a href="attendance.php">
          <i class='bx bx-folder' ></i>
          <i class="fa-solid fa-clipboard-user fa-2xs" style="color: #dedede;"></i>
          <i class="fa-regular fa-calendar-plus fa-2xs" style="color: #dedede;"></i>
          <span class="link_name">Yrs in Service</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="attendance.php">Years in Service</a></li>
        </ul>
      </li>-->
      <li>
        <a href="request.php">
          <!--<i class='bx bx-folder' ></i>-->
          <!--<i class="fa-solid fa-clipboard-user fa-2xs" style="color: #dedede;"></i>-->
          <i class="fa-solid fa-envelope" style="color: #dedede;"></i>
          <span class="link_name">Request</span>
        </a>
        <ul class="sub-menu">
          <li><a class="link_name" href="request.php">Request</a></li>
          <li><a href="request_recent.php">Recent Request</a></li>
        </ul>
      </li>
      <?php if ($_SESSION['access_level'] == 'super admin') { ?>
        <li>
          <div class="iocn-link">
            <a href="manage_employee.php">
            <i class="fa-solid fa-user" style="color: #dedede;"></i>
              <span class="link_name">Manage</span>
            </a>
            <i class="fa-solid fa-chevron-down fa-2xs arrow" style="color: #dedede;"></i>
          </div>
          <ul class="sub-menu">
            <li><a class="link_name" href="manage_employee.php">Manage</a></li>
            <li><a href="manage_employee.php">Manage Employee</a></li>
            <li><a href="manage_admin.php">Manage Admin</a></li>
          </ul>
        </li>
      <?php } ?>
      
      <li>
        <a href="history.php">
          <!--<i class='bx bx-cart-alt' ></i>-->
          <i class="fa-solid fa-clock-rotate-left" style="color: #dedede;"></i>
          <span class="link_name">History</span>
        </a>
        <ul class="sub-menu blank">
          <li><a class="link_name" href="history.php">History</a></li>
        </ul>
      </li>
      
      <li>
        <div class="iocn-link">
          <a href="settings.php">
            <i class="fa-solid fa-gear" style="color: #dedede;"></i>
            <span class="link_name">Settings</span>
          </a>
          <i class="fa-solid fa-chevron-down fa-2xs arrow" style="color: #dedede;"></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="settings.php">Settings</a></li>
          <li><a href="settings.php">Account Settings</a></li>
          <li><a href="settings.php">Input Settings</a></li>
          <?php 
            if ($_SESSION['access_level'] == 'super admin') {
              echo '<li><a href="settings.php">Manage Settings</a></li>';
            }
          ?>
        </ul>
      </li>
      <?php }else { ?>
        <li>
        <div class="iocn-link">
          <a href="information.php?control=<?php echo $_SESSION['control_number']; ?>">
            <i class='bx bx-user' ></i>
            <span class="link_name">Information</span>
          </a>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="information.php?control=<?php echo $_SESSION['control_number']; ?>">My Information</a></li>
        </ul>
      </li>

      <li>
        <div class="iocn-link">
          <a href="settings.php">
            <i class="fa-solid fa-gear" style="color: #dedede;"></i>
            <span class="link_name">Settings</span>
          </a>
          <i class="fa-solid fa-chevron-down fa-2xs arrow" style="color: #dedede;"></i>
        </div>
        <ul class="sub-menu">
          <li><a class="link_name" href="settings.php">Settings</a></li>
          <li><a href="settings_change_username.php?control=<?php echo $_SESSION['control_number']; ?>">Change Username</a></li>
          <li><a href="settings_change_password.php?control=<?php echo $_SESSION['control_number']; ?>">Change Password</a></li>
        </ul>
      </li>
        <?php } ?>
      

      <li>
        <div class="profile-details">
        <div class="profile-button">
          <i class="button2"></i>
          <img class='profile-logo' src="<?php echo "images/".$_SESSION['image']; ?>" alt="">
        </div>
          <div class="name-job">
            <div class="profile_name"><?php echo $_SESSION['name']; ?></div>
            <div class="job"><?php echo ucwords($_SESSION['access_level']) ?></div>
          </div>
          <a href="?logout=true">
            <!--<i class="bx bx-log-out"></i>-->
            <i class="fa-solid fa-arrow-right-from-bracket fa-rotate-180 log-out" style="color: #dedede;"></i>
          </a>
          
        </div>
      </li>
</ul>
  </div>
  <section class="home-section">
    <div class="home-content">
    </div>
  </section>
  <script>
  let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
    arrowParent.classList.toggle("showMenu");
  });
}

let sidebar = document.querySelector(".sidebar");
let sidebarBtn = document.querySelector(".bx-menu-menu");

console.log(sidebarBtn);

sidebarBtn.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});


let sidebarBtn2 = document.querySelector(".button2");
console.log(sidebarBtn2); // You can add any necessary logic for sidebarBtn2 here
sidebarBtn2.addEventListener("click", () => {
  sidebar.classList.toggle("close");
});

  </script>
</body>
</html>
