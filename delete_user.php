<?php
session_start();
include "db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("
    UPDATE users
    SET deleted_at = NOW()
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

/* AUDIT LOG */
$user = $_SESSION['user']['name'];

$log = $conn->prepare("
    INSERT INTO audit_logs
    (action, table_name, record_id, user_name)
    VALUES ('DELETE', 'users', ?, ?)
");

$log->bind_param("is", $id, $user);
$log->execute();

header("Location: users.php");
exit();
?>