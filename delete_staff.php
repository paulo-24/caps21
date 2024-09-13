<?php
include 'php/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare the SQL query
    $sql = "DELETE FROM staff WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);

    // Execute the query
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $connection->error;
    }

    $stmt->close();
}

$connection->close();
?>

<script>
    function deleteStaff(id) {
        if (confirm('Are you sure you want to delete this staff member?')) {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'delete_staff.php?id=' + id, true);
            xhr.onload = function() {
                if (this.status == 200) {
                    if (this.responseText === "success") {
                        // Optionally, remove the staff member from the UI
                        alert('Staff member deleted successfully.');
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        console.error('Error:', this.responseText);
                    }
                } else {
                    console.error('Error:', this.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Request failed');
            };
            xhr.send();
        }
    }
</script>
