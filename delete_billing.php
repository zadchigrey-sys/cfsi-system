<?php
session_start();
include "db.php";

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $user = $_SESSION['user']['name'];

    // ✅ SOFT DELETE (not permanent)
    $stmt = $conn->prepare("UPDATE billings SET deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ✅ LOG
    $log = $conn->prepare("INSERT INTO audit_logs (action, table_name, record_id, user_name)
                           VALUES ('DELETE', 'billings', ?, ?)");
    $log->bind_param("is", $id, $user);
    $log->execute();

    header("Location: billing.php?deleted=1");
    exit();
}
?>