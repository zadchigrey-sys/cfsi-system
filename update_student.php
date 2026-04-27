<?php
include "db.php";

$id = $_POST['id'];
$student_id = $_POST['student_id'];
$name = $_POST['full_name'];
$grade = $_POST['grade_level'];
$section = $_POST['section'];

$stmt = $conn->prepare("UPDATE students SET student_id=?, full_name=?, grade_level=?, section=? WHERE id=?");
$stmt->bind_param("ssssi", $student_id, $name, $grade, $section, $id);
$stmt->execute();

header("Location: students.php");