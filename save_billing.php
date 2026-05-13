<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user']['name'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // GET FORM DATA
    $id = $_POST['id'] ?? '';

    $billing_id = $_POST['billing_id'];
    $student_id = $_POST['student_id'];
    $fee_type = $_POST['fee_type'];
    $status = $_POST['status'];
    $billing_date = $_POST['billing_date'];
    $due_date = $_POST['due_date'];
    $total_amount = $_POST['total_amount'];

    // =========================
    // UPDATE BILLING
    // =========================
    if (!empty($id)) {

        $stmt = $conn->prepare("UPDATE billings SET
                student_id = ?,
                fee_type = ?,
                status = ?,
                billing_date = ?,
                due_date = ?,
                total_amount = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssdi",
            $student_id,
            $fee_type,
            $status,
            $billing_date,
            $due_date,
            $total_amount,
            $id
        );

        $action = "UPDATE";

    } else {

            if ($status == "Paid") {
            $amount_paid = $total_amount;
         $remaining_balance = 0;
        } else {
         $amount_paid = 0;
         $remaining_balance = $total_amount;
        
         }
        // =========================
        // INSERT NEW BILLING
        // =========================
        $stmt = $conn->prepare("INSERT INTO billings
            (
                billing_id,
                student_id,
                fee_type,
                status,
                billing_date,
                due_date,
                total_amount,
                amount_paid,
                remaining_balance
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssssssddd",
            $billing_id,
            $student_id,
            $fee_type,
            $status,
            $billing_date,
            $due_date,
            $total_amount,
            $amount_paid,
            $remaining_balance
        );

        $action = "CREATE";
    }

    // EXECUTE
    if ($stmt->execute()) {

        // =========================
        // AUDIT LOG
        // =========================
        $record_id = !empty($id)
            ? $id
            : $conn->insert_id;

        $log = $conn->prepare("INSERT INTO audit_logs
            (action, table_name, record_id, user_name)
            VALUES (?, 'billings', ?, ?)
        ");

        $log->bind_param(
            "sis",
            $action,
            $record_id,
            $user
        );

        $log->execute();

        header("Location: billing.php?success=1");
        exit();

    } else {

        echo "Database Error: " . $stmt->error;

    }
}
?>