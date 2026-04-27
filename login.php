<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['user'] = $user;

    header("Location: dashboard.php");
    exit();
} else {
        echo "<script>alert('Wrong password'); window.location='index.php';</script>";
    }
} else {
    echo "<script>alert('User not found'); window.location='index.php';</script>";
}
?>