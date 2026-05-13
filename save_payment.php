<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // GET FORM DATA
    $id = $_POST['id'] ?? '';

    $student_id = $_POST['student_id'];
    $billing_id = $_POST['billing_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    // =========================
    // AUTO GENERATE PAYMENT ID
    // =========================
    if (empty($id)) {
        $payment_id = uniqid("PAY");
    } else {
        $payment_id = $_POST['payment_id'];
    }

    // =========================
    // UPDATE PAYMENT
    // =========================
    if (!empty($id)) {

        $stmt = $conn->prepare("UPDATE payments 
            SET 
                student_id = ?,
                billing_id = ?,
                amount_paid = ?,
                payment_method = ?,
                payment_date = ?,
                status = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssdsssi",
            $student_id,
            $billing_id,
            $amount_paid,
            $payment_method,
            $payment_date,
            $status,
            $id
        );

    } else {

        // =========================
        // INSERT NEW PAYMENT
        // =========================
        $stmt = $conn->prepare("INSERT INTO payments
            (
                payment_id,
                student_id,
                billing_id,
                amount_paid,
                payment_method,
                payment_date,
                status
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssdsss",
            $payment_id,
            $student_id,
            $billing_id,
            $amount_paid,
            $payment_method,
            $payment_date,
            $status
        );
    }

    // EXECUTE
    if ($stmt->execute()) {

        // =========================
        // UPDATE BILLING BALANCE
        // =========================

        $getPaid = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) AS total_paid
            FROM payments
            WHERE billing_id = ? AND deleted_at IS NULL
        ");

        $getPaid->bind_param("s", $billing_id);
        $getPaid->execute();

        $total_paid = $getPaid->get_result()->fetch_assoc()['total_paid'];

        $getBilling = $conn->prepare("SELECT total_amount
            FROM billings
            WHERE billing_id = ?
        ");

        $getBilling->bind_param("s", $billing_id);
        $getBilling->execute();

        $total_amount = $getBilling->get_result()->fetch_assoc()['total_amount'];

        $remaining_balance = $total_amount - $total_paid;

        // =========================
// BILLING STATUS LOGIC
// =========================

if ($status == "Pending") {

    // Pending payments should remain outstanding
    $billing_status = "Pending";

} else {

    // Completed payment logic
    if ($total_paid >= $total_amount) {
        $billing_status = "Paid";
    } else {
        $billing_status = "Pending";
    }
} 

        $updateBilling = $conn->prepare("UPDATE billings SET amount_paid = ?,remaining_balance = ?, status = ? WHERE billing_id = ?");

        $updateBilling->bind_param(
            "ddss",
            $total_paid,
            $remaining_balance,
            $billing_status,
            $billing_id
        );

        $updateBilling->execute();

        header("Location: payments.php?success=1");
        exit();

    } else {

        echo "Database Error: " . $stmt->error;

    }
}
?>