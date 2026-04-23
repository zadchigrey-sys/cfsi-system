<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

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