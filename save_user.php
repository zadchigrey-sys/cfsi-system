<?php
session_start();
include "db.php";

if ($_SESSION['user']['role'] !== 'administrator') {
    die("Access denied.");
}

$id = $_POST['id'] ?? '';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

if (empty($id)) {

    /* ADD USER */

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss", $name, $email, $hashed, $role);

    $stmt->execute();

} else {

    /* UPDATE USER */

    if (!empty($password)) {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            UPDATE users
            SET name=?, email=?, password=?, role=?
            WHERE id=?
        ");

        $stmt->bind_param("ssssi",
            $name,
            $email,
            $hashed,
            $role,
            $id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE users
            SET name=?, email=?, role=?
            WHERE id=?
        ");

        $stmt->bind_param("sssi",
            $name,
            $email,
            $role,
            $id
        );
    }

    $stmt->execute();
}

header("Location: users.php");
exit();
?>