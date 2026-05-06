<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $payment_id = $_POST['payment_id'];
    $student_id = $_POST['student_id'];
    $billing_id = $_POST['billing_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_method = $_POST['payment_method'];
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("

    if (!$id) {
    $payment_id = "PAY" . time();
}
    if (!$id) {
    // Check duplicate
    $check = $conn->prepare("SELECT id FROM payments WHERE payment_id = ?");
    $check->bind_param("s", $payment_id);
    $check->execute();
    
    if ($check->get_result()->num_rows > 0) {
        die("Duplicate Payment ID detected!");
    }
}
        INSERT INTO payments 
        (payment_id, student_id, billing_id, amount_paid, payment_method, payment_date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssisss", 
        $payment_id, 
        $student_id, 
        $billing_id, 
        $amount_paid, 
        $payment_method, 
        $payment_date, 
        $status
    );

    if ($stmt->execute()) {
        header("Location: payments.php?success=1");
    } else {
        echo "Error: " . $stmt->error;
    }
}