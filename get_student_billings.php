<?php
include "db.php";

$student_id = $_GET['student_id'];

$stmt = $conn->prepare("SELECT *
    FROM billings
    WHERE student_id = ?
    AND deleted_at IS NULL
    AND remaining_balance > 0
");

$stmt->bind_param("s", $student_id);
$stmt->execute();

$result = $stmt->get_result();

$data = [];

while($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>