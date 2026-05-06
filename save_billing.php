<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user']['name'];

// Get form data
$id = $_POST['id'] ?? null;
$payment_id = $_POST['payment_id'];
$student_id = $_POST['student_id'];
$billing_id = $_POST['billing_id'];
$amount = $_POST['amount_paid'];
$method = $_POST['payment_method'];
$date = $_POST['payment_date'];
$status = $_POST['status'];

if ($id) {
    // ✅ UPDATE EXISTING PAYMENT
    $stmt = $conn->prepare("UPDATE payments 
        SET payment_id=?, student_id=?, billing_id=?, amount_paid=?, payment_method=?, payment_date=?, status=? 
        WHERE id=?");
    $stmt->bind_param("sssisssi", $payment_id, $student_id, $billing_id, $amount, $method, $date, $status, $id);
    $stmt->execute();

    $action = "UPDATE";
} else {
    // ✅ INSERT NEW PAYMENT
    $stmt = $conn->prepare("INSERT INTO payments 
        (payment_id, student_id, billing_id, amount_paid, payment_method, payment_date, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssisss", $payment_id, $student_id, $billing_id, $amount, $method, $date, $status);
    $stmt->execute();

    $action = "CREATE";
}

// ==============================
// 🔥 AUTO UPDATE BILLING BALANCE
// ==============================

// 1. Get total paid for this billing
$stmt = $conn->prepare("
    SELECT COALESCE(SUM(amount_paid),0) as total_paid 
    FROM payments 
    WHERE billing_id = ? AND deleted_at IS NULL
");
$stmt->bind_param("s", $billing_id);
$stmt->execute();
$total_paid = $stmt->get_result()->fetch_assoc()['total_paid'];

// 2. Get total billing amount
$stmt = $conn->prepare("SELECT total_amount FROM billings WHERE billing_id = ?");
$stmt->bind_param("s", $billing_id);
$stmt->execute();
$total_amount = $stmt->get_result()->fetch_assoc()['total_amount'];

// 3. Compute balance
$remaining = $total_amount - $total_paid;

// 4. Update billing
$stmt = $conn->prepare("
    UPDATE billings 
    SET amount_paid = ?, remaining_balance = ?, status = ?
    WHERE billing_id = ?
");

// Auto status logic
if ($remaining <= 0) {
    $billing_status = "Paid";
} elseif ($total_paid > 0) {
    $billing_status = "Partial";
} else {
    $billing_status = "Pending";
}

$stmt->bind_param("ddss", $total_paid, $remaining, $billing_status, $billing_id);
$stmt->execute();


$student_id = $_POST['student_id'];


$stmt->bind_param("ssssssd",
    $billing_id,
    $student_id, 
    $status
);
// ==============================
// ✅ AUDIT LOG
// ==============================
$log = $conn->prepare("INSERT INTO audit_logs (action, table_name, record_id, user_name)
                       VALUES (?, 'payments', ?, ?)");
$log->bind_param("sis", $action, $id, $user);
$log->execute();

header("Location: payments.php?success=1");
exit();
?>