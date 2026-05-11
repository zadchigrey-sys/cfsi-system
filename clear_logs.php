<?php
session_start();
include "db.php";

// DELETE ALL LOGS
$conn->query("DELETE FROM audit_logs");

// OPTIONAL: RESET AUTO INCREMENT
$conn->query("ALTER TABLE audit_logs AUTO_INCREMENT = 1");

// REDIRECT BACK
header("Location: logs.php?cleared=1");
exit();
?>