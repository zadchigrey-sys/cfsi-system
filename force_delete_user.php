<?php
include "db.php";

$id = $_GET['id'];

$stmt = $conn->prepare("
    DELETE FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: recycle_bin.php");
exit();
?>