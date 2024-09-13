<?php
include 'php/database.php'; // Include your database connection

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "SELECT * FROM staff WHERE id = ?";
    $stmt = $connection->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(['error' => 'Failed to prepare statement: ' . $connection->error]);
        exit;
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $staff = $result->fetch_assoc();
        echo json_encode($staff);
    } else {
        echo json_encode(['error' => 'Staff not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'ID not provided']);
}

$connection->close();
?>
