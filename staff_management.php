<?php
include 'php/database.php';  // Include your database connection

// Check if the form is submitted to add an admin
if (isset($_POST['add_admin'])) {
    // Retrieve form data and sanitize it
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $gender = htmlspecialchars(trim($_POST['gender']));
    $password = $_POST['password'];  // Password should not be sanitized this way
    $confirm_password = $_POST['confirm_password'];
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        // Check if the email already exists
        $sql = "SELECT id FROM staff WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<div class='alert alert-danger'>Email already in use.</div>";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // SQL query to insert a new admin into the 'staff' table
            $sql = "INSERT INTO staff (full_name, username, email, password, gender) VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sssss", $full_name, $username, $email, $hashed_password, $gender);

            // Execute the query and check for success
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Admin added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error adding admin: " . $connection->error . "</div>";
            }
        }

        $stmt->close();
    }
}

// Fetch staff data to display in the table
$sql = "SELECT * FROM staff";
$result = $connection->query($sql);

// Fetch data into an array
$staffData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $staffData[] = $row;
    }
}
$connection->close();
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WMSU - CCS | Comprehensive Student Management System</title>
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
            crossorigin="anonymous">
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <link
            href="external/css/admin_staffManagement.css"
            rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32"
            href="external/img/favicon-32x32.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">

        <link rel="stylesheet"
            href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"></script>
        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
            integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
            crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- DataTables JS -->
        <script
            src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </head>

    <style>

        table.dataTable {
            font-size: 12px;
        }

        td{
            text-align: center;
            vertical-align: middle;
        }

        .btn-csms{
            background-color: #709775;
            color: white;
        }

    </style>

    <body>
        <div class="container-fluid whole-container">
            <div class="row">

                <div class="col-md-2 sidebar text-center">
                    <small id="currentTime"> </small>

                    <img src="external/img/ccs_logo-removebg-preview.png"
                        class="img-fluid logo space-sm">
                    <h4 class="bold">Welcome, Admin!</h4>

                    <div class="navigation-links" style="text-align: left;">
                        <span><i class="bi bi-house"></i> Home</span>
                        <a href="index.html"><p><i class="bi bi-kanban"></i> Index</p></a>
                        <hr>
                        <span><i class="bi bi-menu-button-wide"></i> Management</span>
                        <a href="class_management.html"><p><i class="bi bi-book"></i> Class Management</p></a>
                        <a href="staff_management.php"><p><i class="bi bi-person-square"></i> Admin Management</p></a>
                        <a href="subject_management.html"><p><i class="bi bi-journals"></i> Subject Management</p></a>
                        <a href="semester_management.html"><p><i class="bi bi-calendar-event"></i> Semester Management</p></a>
                        <hr>
                      </div>
                </div>
                <div class="col">
                    <div
                        class="container-fluid d-flex navbar navbar-expand-lg">
                        <a class="navbar-brand" href="#">
                            <img
                                src="external/img/ccs_logo-removebg-preview.png"
                                class="img-fluid small-logo">
                        </a>
                        <div class="mx-auto c-white">
                            Comprehensive Student Management System
                        </div>
                        <button class="navbar-toggler" type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent"
                            aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse"
                            id="navbarSupportedContent">
                            <div class="ms-auto">
                                <span class="ms-auto">
                                    <a href
                                        class="nav-link-spand position-relative "
                                        data-bs-toggle="modal"
                                        data-bs-target="#messageModal">

                                        <i
                                            class="bi bi-chat-dots iconics"></i>
                                        <span
                                            class="position-absolute 
                                            top-0 start-50 translate-middle p-2 
                                            bg-danger border border-light rounded-circle">

                                        </span>
                                    </a>
                                    <a
                                        class="nav-link-span position-relative"><i
                                            class="bi bi-bell iconics"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notificationModal"></i>

                                        <span
                                            class="position-absolute 
                                            top-0 start-50 translate-middle p-2 
                                            bg-danger border border-light rounded-circle">

                                        </span>

                                    </a>
                                    <a href class="nav-link-span">
                                        <i
                                            class="bi bi-person-circle iconics"></i></a>

                                </span>

                            </div>
                        </div>
                    </div>

                    <div class="container-fluid actual-content">
                        <div class="container wd-75">
                            <div class="welcome-container">

                                <div class="d-flex align-items-center">
                                    <h3>Welcome to your Dashboard!

                                        <p class="fs-small mt-10">Welcome back!
                                            Here's
                                            to another day of making a
                                            difference in our students'
                                            lives.</p>

                                        <p class="fs-small mt-10"> <a
                                                href="index.html"
                                                class="nav-ham-link">Home</a> /
                                            Staff Management</p>
                                    </h3>

                                    <div class="ms-auto" aria-hidden="true">
                                        <img
                                            src="external/svgs/undraw_favorite_gb6n.svg"
                                            class=" small-picture img-fluid">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <h5>Admin Account List</h5>
                                <div class="ms-auto" aria-hidden="true">
                                    <button type="button" class="btn btn-csms"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addAdminModal"><i class="bi bi-pencil-square"></i>Add an Admin</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <table id="staffTable" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <!-- <th>Gender</th> -->
                                   
                                    <!-- <th>Password</th> -->
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($staffData as $staff): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($staff['id']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['full_name']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['username']); ?></td>
                                    <td><?php echo htmlspecialchars($staff['email']); ?></td>
                                    <!-- <td><?php echo htmlspecialchars($staff['gender']); ?></td> -->
                                    <!-- <td>
                                        <span data-password="<?php echo htmlspecialchars($staff['password']); ?>" data-hidden="true"></span>
                                        <button class="btn" onclick="togglePassword(this)">Show</button>
                                    </td> -->
                                    <td>
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-warning" onclick="editStaff(<?php echo $staff['id']; ?>)">
                                <i class="bi bi-pencil"></i> Edit</button>
                                    <button type="button" onclick="deleteStaff(<?php echo $staff['id']; ?>)" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <div class="modal fade" id="notificationModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"
                        id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notificationModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Notifications</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="d-flex align-items-center">
            <span>Notifications</span>
            <div class=" ms-auto" aria-hidden="true"><a href
                class="nav-ham-link"> View All</a> | <a href
                class="nav-ham-link"> Read All</a></div>
          </div>

          <br>

          <div class="row ">
            <div class="col-sm-2 text-center">
              <h1><i class="bi bi-bell-fill"></i></h1>
            </div>
            <div class="col">
              <h5>You have received a new notification!</h5>
              <p>Test notification!</p>
            </div>
          </div>

          <br>

          <div class="row ">
            <div class="col-sm-2 text-center">
              <h1><i class="bi bi-bell-fill"></i></h1>
            </div>
            <div class="col">
              <h5>You have received a new notification!</h5>
              <p>Test notification!</p>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="messageModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Chats</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal"
            aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <div class="row msg align-items-center" data-bs-target="#actualMessageModal" data-bs-toggle="modal">
            <div class="col-sm-3 text-center" style="border-right: 1px solid black;">
              <h1><i class="bi bi-person-fill"></i></h1>
              <span>Jason Catadman</span>
            </div>
            <div class="col">
              <p><em>You: Sure sir Catadman. I will work on that right now.</em></p>
            </div>
          </div>

          <br>

          <div class="row unread msg align-items-center">
            <div class="col-sm-3 text-center" style="border-right: 1px solid black;">
              <h1><i class="bi bi-person-fill"></i></h1>
              <span>Ceed Lorenzo</span>
            </div>
            <div class="col">
              <p><em>Lorenzo: Good afternoon sir, ask ko lang if available po si...</em></p>
            </div>
          </div>
          
       

        </div>

      </div>
    </div>
  </div>

  <div class="modal fade" id="actualMessageModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Chats</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="chatBody">
          <div class="time text-center grey">
            2:09 PM - 8/11/2024
          </div>
          <br>
          <div class="row sender">
            <div class="col">
              <i class="bi bi-person-fill"></i>
              <div class="message">
                <span>Hi, this is Jason Catadman. I'd like to switch from Web Technologies to Software Engineering, thanks!</span>
              </div>
            </div>
          </div>
          <br>
          <div class="row receiver">
            <div class="col">
              <div class="message">
                <span>Sure sir Catadman. I will work on that right now.</span>
              </div>
              <i class="bi bi-person"></i>
            </div>
          </div>
          <br>
        </div>
        
          <div class="modal-footer">
          <form id="messageForm">
            <div class="d-flex align-items-center">
              <textarea id="messageInput" cols="45"></textarea>
              <div class="ms-auto" aria-hidden="true" style="margin-left: 10px">
                <input type="submit" value="Send">
              </div>
            </div>
            </div>
          </form>
    
      </div>
    </div>
  </div>

    <div class="modal fade" id="notesModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"
                        id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="remindersModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"
                        id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save
                        changes</button>
                </div>
            </div>
        </div>
    </div>
                                <!-- ADD ADMIN MODAL -->
    <div class="modal fade" id="addAdminModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"
                        id="exampleModalLabel">Add a new Admin</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="staff_management.php">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>                  
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control"
                                id="password" name="password" required style="width: 100%; display: inline !important;"></input>
                                <span style="
                                position: absolute;
                                display: inline; 
                                right: 5%; 
                                margin-top: 5px !important;
                                vertical-align: middle"><i class="bi bi-eye-slash-fill" id="togglerPassword"></i></span>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control"
                                id="confirm_password" name="confirm_password" required style="width: 100%; display: inline !important;"></input>
                                <span style="
                                position: absolute;
                                display: inline; 
                                right: 5%; 
                                margin-top: 5px !important;
                                vertical-align: middle"><i class="bi bi-eye-slash-fill" id="togglerConfirmPassword"></i></span>           
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" name="gender" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>                               
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-csms" value="Add" name="add_admin">
                        <button type="button" class="btn btn-csms" data-bs-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                                <!-- EDIT MODAL-->

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Editing Staff Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="update_staff.php">
                        <div class="mb-3">
                        <label for="edit_id"class="form-label">ID</label>
                        <input type="text" id="edit_id" name="edit_id">
                        </div>
                        <div class="mb-3">
                            <label for="edit_full_name"class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="edit_full_name" name="edit_full_name">
                        </div>
                        <div class="mb-3">
                            <label for="edit_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" name="edit_username">
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="edit_email">
                        </div>
                        <div class="mb-3">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control"
                                id="edit_password" name="edit_password" style="width: 100%; display: inline !important;"></input>
                                <span style="
                                position: absolute;
                                display: inline; 
                                right: 5%; 
                                margin-top: 5px !important;
                                vertical-align: middle"><i class="bi bi-eye-slash-fill" id="togglerPassword"></i></span>
                        </div>
                        <div class="mb-3">
                            <label for="edit_confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control"
                                id="edit_confirm_password" name="edit_confirm_password" style="width: 100%; display: inline !important;"></input>
                                <span style="
                                position: absolute;
                                display: inline; 
                                right: 5%; 
                                margin-top: 5px !important;
                                vertical-align: middle"><i class="bi bi-eye-slash-fill" id="togglerConfirmPassword"></i></span>         
                        </div>
                        <div class="mb-3">
                            <label for="edit_gender" class="form-label">Gender</label>
                            <select class="form-control" id="edit_gender" name="edit_gender">
                                <!-- <option>Select a gender</option> -->
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Others">Others</option>
                            </select>                          
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script>
function editStaff(id) {
    // Fetch staff data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_staff.php?id=' + id, true);
    xhr.onload = function() {
        if (this.status == 200) {
            var staff = JSON.parse(this.responseText);

            if (staff.error) {
                console.error('Error:', staff.error);
                alert('Error fetching staff data: ' + staff.error);
                return;
            }

            // Populate the modal fields
            document.getElementById('edit_id').value = staff.id;
            document.getElementById('edit_full_name').value = staff.full_name;
            document.getElementById('edit_username').value = staff.username;
            document.getElementById('edit_email').value = staff.email;
            document.getElementById('edit_gender').value = staff.gender;
        } else {
            console.error('Error:', this.statusText);
            alert('Error fetching staff data.');
        }
    };
    xhr.onerror = function() {
        console.error('Request failed');
        alert('Request failed.');
    };
    xhr.send();
}
</script>




    <script>

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        function getTime()
        {
            const now = new Date();
            const newTime = now.toLocaleString();
            console.log(newTime);
            document.querySelector("#currentTime").textContent = "The current date and time is: " + newTime;
        }
      
        setInterval(getTime, 100);

        $(document).ready(function() {
    $('#classes').DataTable();
});

$(document).ready(function() {
    $('#assignedClasses').DataTable();
});


 document.addEventListener('DOMContentLoaded', function () {
            const semesterSelect = document.getElementById('semester');
            const availableClassesSelect = document.getElementById('availableClasses');
            const assignedClassesContainer = document.getElementById('assignedClassesContainer');
            const assignedClassesBody = document.getElementById('assignedClassesBody');

            const classes = {
                "First Semester": ["BSIT-1A", "BSIT-1B", "BSIT-2A"],
                "Second Semester": ["BSIT-3A", "BSIT-3B", "BSIT-4A"]
            };

            // Update available classes based on selected semester
            semesterSelect.addEventListener('change', function () {
                const selectedSemester = semesterSelect.value;

                // Clear previous options
                availableClassesSelect.innerHTML = '<option selected disabled>Select a Class</option>';

                // Add new options based on selected semester
                if (classes[selectedSemester]) {
                    classes[selectedSemester].forEach(function (className) {
                        const option = document.createElement('option');
                        option.value = className;
                        option.textContent = className;
                        availableClassesSelect.appendChild(option);
                    });
                }

                checkSelections();
            });

            // Show/hide assigned classes section based on selections
            function checkSelections() {
                if (semesterSelect.value !== 'Select a Semester' && availableClassesSelect.value !== 'Select a Class') {
                    // Filter and show only relevant rows
                    const selectedSemester = semesterSelect.value;
                    const selectedClass = availableClassesSelect.value;
                    let hasRelevantClasses = false;

                    [...assignedClassesBody.getElementsByTagName('tr')].forEach(row => {
                        if (row.dataset.semester === selectedSemester && row.dataset.class === selectedClass) {
                            row.style.display = '';
                            hasRelevantClasses = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (hasRelevantClasses) {
                        assignedClassesContainer.style.display = 'block';
                    } else {
                        assignedClassesContainer.style.display = 'none';
                    }
                } else {
                    assignedClassesContainer.style.display = 'none';
                }
            }

            // Remove class
            document.querySelectorAll('.remove-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const row = this.closest('tr');
                    row.parentNode.removeChild(row);
                    checkSelections();
                });
            });

            // Also check the selections when the class dropdown changes
            availableClassesSelect.addEventListener('change', checkSelections);
        });
        function hidePasswordsOnLoad() {
    var passwordCells = document.querySelectorAll('span[data-password]');
    passwordCells.forEach(function(passwordCell) {
        var password = passwordCell.getAttribute('data-password');
        passwordCell.textContent = '*'.repeat(password.length);
        passwordCell.setAttribute('data-hidden', 'true');
    });
}

function togglePassword(passwordId, button) {
    var passwordCell = document.getElementById(passwordId);
    var isHidden = passwordCell.getAttribute('data-hidden') === 'true';

    if (isHidden) {
        passwordCell.textContent = passwordCell.getAttribute('data-password');
        button.textContent = "Hide";
        passwordCell.setAttribute('data-hidden', 'false');
    } else {
        passwordCell.textContent = '*'.repeat(passwordCell.getAttribute('data-password').length);
        button.textContent = "Show";
        passwordCell.setAttribute('data-hidden', 'true');
    }
}

// Hide passwords when the page loads
window.onload = hidePasswordsOnLoad;

const togglerPassword = document
            .querySelector('#togglerPassword');
        const password = document.querySelector('#password');
        togglerPassword.addEventListener('click', () => {
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            password.setAttribute('type', type);
            togglerPassword.classList.toggle('bi-eye');
        });

        const togglerConfirmPassword = document
            .querySelector('#togglerConfirmPassword');
        const confirmPassword = document.querySelector('#confirm_password');
        togglerConfirmPassword.addEventListener('click', () => {
            const type = password
                .getAttribute('type') === 'password' ?
                'text' : 'password';
                confirmPassword.setAttribute('type', type);
            togglerConfirmPassword.classList.toggle('bi-eye');
        });

        function deleteModal(){
            Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    Swal.fire({
      title: "Deleted!",
      text: "Your record has been deleted.",
      icon: "success"
    });
  }
});
        }
        
    </script>

    <script>
             document.getElementById('messageForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form submission

    // Get the message from the textarea
    var messageText = document.getElementById('messageInput').value;

    // If the message is not empty, append it to the chat
    if (messageText.trim() !== '') {
      var chatBody = document.getElementById('chatBody');

      // Create the new message element
      var newMessage = document.createElement('div');
      newMessage.className = 'row receiver'; // Set it as a sender message
      newMessage.innerHTML = `
      
            <div class="col">
              <div class="message">
                 <span>${messageText}</span>
              </div>
              <i class="bi bi-person"></i>
            </div>
      
      
      `;

      // Append the new message to the chat body
      chatBody.appendChild(newMessage);

      // Clear the textarea
      document.getElementById('messageInput').value = '';

      // Scroll to the bottom of the chat
      chatBody.scrollTop = chatBody.scrollHeight;

    }
  });
    </script>
     <script>
        function togglePassword(button) {
            const passwordSpan = button.previousElementSibling;
            if (passwordSpan.dataset.hidden === "true") {
                passwordSpan.dataset.hidden = "false";
                button.textContent = "Hide";
            } else {
                passwordSpan.dataset.hidden = "true";
                button.textContent = "Show";
            }
        }

        function editStaff(id) {
            // Fetch staff details and populate edit modal
            fetch(`get_staff.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editFullName').value = data.full_name;
                    document.getElementById('editUsername').value = data.username;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editGender').value = data.gender;
                    document.getElementById('editId').value = data.id;
                });
        }

        function deleteStaff(id) {
            if (confirm("Are you sure you want to delete this staff?")) {
                fetch(`delete_staff.php?id=${id}`, { method: 'DELETE' })
                    .then(response => response.text())
                    .then(result => {
                        location.reload(); // Reload page after deletion
                    });
            }
        }
    </script>
</html>