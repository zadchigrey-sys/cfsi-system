<?php
include "db.php";

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

// Check if email exists
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$check = $stmt->get_result();

if ($check->num_rows > 0) {
    echo "<script>alert('Email already exists!'); window.location='index.php';</script>";
    exit();
}

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
    header("Location: index.php?success=1");
    exit();
} else {
    echo "Error inserting data: " . $stmt->error;
}
} else {
    echo "Prepare failed: " . $conn->error;
}
?>