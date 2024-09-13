<?php
include 'php/database.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $middle_name = mysqli_real_escape_string($connection, $_POST['middle_name']);
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($connection, $_POST['confirm_password']);

    // Check if passwords match
    if ($password != $confirm_password) {
        die("Passwords do not match. Please go back and try again.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    //Concatenate names to create full name
    $full_name = $first_name . ' ' . $middle_name . ' ' . $last_name;

    // Insert into the database
    $sql = "INSERT INTO staff (last_name, first_name, username, middle_name, email, password, full_name) 
            VALUES ('$last_name', '$first_name', '$username', '$middle_name', '$email', '$hashed_password', '$full_name')";

    if (mysqli_query($connection, $sql)) {
        // Redirect to the login page after account creation
        header("Location: admin_login_page.html");
        exit(); // Make sure to exit after the redirect to prevent further code execution
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }

    // Close the database connection
    mysqli_close($connection);
}
?>