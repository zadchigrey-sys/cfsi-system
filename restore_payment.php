<?php
session_start();
include "db.php";

$id = $_GET['id'];
$user = $_SESSION['user']['name'];

// ✅ RESTORE
$conn->query("UPDATE payments SET deleted_at = NULL WHERE id = $id");

// ✅ LOG
$conn->query("INSERT INTO audit_logs (action, table_name, record_id, user_name)
VALUES ('RESTORE', 'payments', $id, '$user')");

header("Location: recycle_bin.php");
?>