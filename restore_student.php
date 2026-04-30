<?php
session_start();
include "db.php";

$id = $_GET['id'];
$user = $_SESSION['user']['name'];

$conn->query("UPDATE students SET deleted_at = NULL WHERE id = $id");

$conn->query("INSERT INTO audit_logs (action, table_name, record_id, user_name)
VALUES ('RESTORE', 'students', $id, '$user')");

header("Location: recycle_bin.php");