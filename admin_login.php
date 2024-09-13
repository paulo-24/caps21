<?php
session_start();
include 'php/database.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Query to get user data
    $sql = "SELECT * FROM staff WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);

    // Check if the query ran successfully
    if (!$result) {
        die("Database query failed: " . mysqli_error($connection)); // Debugging: Display the error if the query fails
    }

    // Check if a user was found
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables and redirect to dashboard
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.html");
            exit(); // Make sure to exit after the redirect
        } else {
            echo "Invalid username or password. (Incorrect password)"; // Debugging: Incorrect password
        }
    } else {
        echo "Invalid username or password. (User not found)"; // Debugging: No such user
    }

    // Close the database connection
    mysqli_close($connection);
}
?>
