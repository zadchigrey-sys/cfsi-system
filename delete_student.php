<?php
session_start();
include "db.php";

// Protect page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Validate ID
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    // Prepared statement (SECURE)
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error deleting student: " . $stmt->error;
    }

} else {
    echo "Invalid request.";
}
?>