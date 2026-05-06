<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $user = $_SESSION['user']['name'];

    // ✅ SOFT DELETE
    $stmt = $conn->prepare("UPDATE payments SET deleted_at = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // ==============================
// 🔥 RECOMPUTE BILLING
// ==============================

$stmt = $conn->prepare("
    SELECT COALESCE(SUM(amount_paid),0) as total_paid 
    FROM payments 
    WHERE billing_id = ? AND deleted_at IS NULL
");
$stmt->bind_param("s", $billing_id);
$stmt->execute();
$total_paid = $stmt->get_result()->fetch_assoc()['total_paid'];

$stmt = $conn->prepare("SELECT total_amount FROM billings WHERE billing_id = ?");
$stmt->bind_param("s", $billing_id);
$stmt->execute();
$total_amount = $stmt->get_result()->fetch_assoc()['total_amount'];

$remaining = $total_amount - $total_paid;

if ($remaining <= 0) {
    $billing_status = "Paid";
} elseif ($total_paid > 0) {
    $billing_status = "Partial";
} else {
    $billing_status = "Pending";
}

$stmt = $conn->prepare("
    UPDATE billings 
    SET amount_paid = ?, remaining_balance = ?, status = ?
    WHERE billing_id = ?
");
$stmt->bind_param("ddss", $total_paid, $remaining, $billing_status, $billing_id);
$stmt->execute();

    // ✅ AUDIT LOG
    $log = $conn->prepare("INSERT INTO audit_logs (action, table_name, record_id, user_name)
                           VALUES ('DELETE', 'payments', ?, ?)");
    $log->bind_param("is", $id, $user);
    $log->execute();

    header("Location: payments.php?deleted=1");
    exit();
}
?>