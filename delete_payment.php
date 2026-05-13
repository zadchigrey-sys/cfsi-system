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

    // GET BILLING ID
$getBilling = $conn->prepare("SELECT billing_id FROM payments WHERE id = ?
");
$getBilling->bind_param("i", $id);
$getBilling->execute();

$billing = $getBilling->get_result()->fetch_assoc();
$billing_id = $billing['billing_id'];

// SOFT DELETE
$delete = $conn->prepare("UPDATE payments SET deleted_at = NOW() WHERE id = ?");
$delete->bind_param("i", $id);
$delete->execute();


// =========================
// RECALCULATE BILLING
// =========================

$getPaid = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) AS total_paid
    FROM payments WHERE billing_id = ?
    AND deleted_at IS NULL AND status = 'Completed'");

$getPaid->bind_param("s", $billing_id);
$getPaid->execute();

$total_paid = $getPaid->get_result()->fetch_assoc()['total_paid'];

$getTotal = $conn->prepare("SELECT total_amount FROM billings WHERE billing_id = ?");

$getTotal->bind_param("s", $billing_id);
$getTotal->execute();

$total_amount = $getTotal->get_result()->fetch_assoc()['total_amount'];

$remaining_balance = $total_amount - $total_paid;


// STATUS
if ($total_paid <= 0) {
    $billing_status = "Unpaid";
}
elseif ($remaining_balance <= 0) {
    $billing_status = "Paid";
}
else {
    $billing_status = "Pending";
}


// UPDATE BILLING
$update = $conn->prepare("UPDATE billings SET amount_paid = ?,remaining_balance = ?,
        status = ? WHERE billing_id = ?");

$update->bind_param(
    "ddss",
    $total_paid,
    $remaining_balance,
    $billing_status,
    $billing_id
);

$update->execute();

    // ✅ AUDIT LOG
    $log = $conn->prepare("INSERT INTO audit_logs (action, table_name, record_id, user_name)
                           VALUES ('DELETE', 'payments', ?, ?)");
    $log->bind_param("is", $id, $user);
    $log->execute();

    header("Location: payments.php?deleted=1");
    exit();
}
?>