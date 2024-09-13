<?php
include('php/database.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];

    // Prepare and execute the query to get user data
    $stmt = $connection->prepare("SELECT password_hash FROM teachers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($password_hash);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $password_hash)) {
            // Password is correct, redirect to the dashboard
            header("Location: teacher_dashboard.html");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    $stmt->close();
    $connection->close();
}
?>
