<?php
include 'php/database.php';

// Debugging: Print the entire POST array to verify data
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : '';
    $full_name = isset($_POST['edit_full_name']) ? htmlspecialchars(trim($_POST['edit_full_name'])) : '';
    $username = isset($_POST['edit_username']) ? htmlspecialchars(trim($_POST['edit_username'])) : '';
    $email = isset($_POST['edit_email']) ? htmlspecialchars(trim($_POST['edit_email'])) : '';
    $password = isset($_POST['edit_password']) ? $_POST['edit_password'] : '';
    $confirm_password = isset($_POST['edit_confirm_password']) ? $_POST['edit_confirm_password'] : '';
    $gender = isset($_POST['edit_gender']) ? htmlspecialchars(trim($_POST['edit_gender'])) : '';

    // Check if the required fields are not empty
    if (empty($id) || empty($full_name) || empty($username) || empty($email) || empty($gender)) {
        echo "<div class='alert alert-danger'>Please fill in all required fields.</div>";
        exit;
    }

    // Check if passwords match, if provided
    if (!empty($password) && $password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Passwords do not match.</div>";
        exit;
    }

    // Prepare the SQL query
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE staff SET full_name=?, username=?, email=?, password=?, gender=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            echo "<div class='alert alert-danger'>Error preparing statement: " . $connection->error . "</div>";
            exit;
        }
        $stmt->bind_param("sssssi", $full_name, $username, $email, $hashed_password, $gender, $id);
    } else {
        $sql = "UPDATE staff SET full_name=?, username=?, email=?, gender=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            echo "<div class='alert alert-danger'>Error preparing statement: " . $connection->error . "</div>";
            exit;
        }
        $stmt->bind_param("ssssi", $full_name, $username, $email, $gender, $id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Staff updated successfully!</div>";
        header("Location: staff_management.php"); // Redirect after successful update
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error updating staff: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$connection->close();
?>
