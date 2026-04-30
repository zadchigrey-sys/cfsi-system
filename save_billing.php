<?php
include "db.php";

$id = $_POST['id'] ?? null;

$billing_id = $_POST['billing_id'];
$status = $_POST['status'];
$billing_date = $_POST['billing_date'];
$due_date = $_POST['due_date'];
$fee_type = $_POST['fee_type'];
$total_amount = $_POST['total_amount'];

if (!empty($id)) {

    // UPDATE
    $stmt = $conn->prepare("UPDATE billings 
        SET billing_id=?, status=?, billing_date=?, due_date=?, fee_type=?, total_amount=?
        WHERE id=?");

    $stmt->bind_param("sssssdi",
        $billing_id,
        $status,
        $billing_date,
        $due_date,
        $fee_type,
        $total_amount,
        $id
    );

} else {

    // INSERT
    $stmt = $conn->prepare("INSERT INTO billings 
        (billing_id, status, billing_date, due_date, fee_type, total_amount)
        VALUES (?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("sssssd",
        $billing_id,
        $status,
        $billing_date,
        $due_date,
        $fee_type,
        $total_amount
    );
}

$stmt->execute();

header("Location: billing.php");
exit();
?>