<?php
session_start();
include "db.php";

$id = $_GET['id'];
$user = $_SESSION['user']['name'];

$conn->query("DELETE FROM billings WHERE id = $id");

// LOG
$conn->query("INSERT INTO audit_logs (action, table_name, record_id, user_name)
VALUES ('FORCE_DELETE', 'billings', $id, '$user')");

header("Location: recycle_bin.php?deleted=1");