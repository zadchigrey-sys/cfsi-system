<?php
include "db.php";

$id = $_POST['id'] ?? null;

$student_id = $_POST['student_id'];
$full_name = $_POST['full_name'];
$grade_level = $_POST['grade_level'];
$section = $_POST['section'];

if (!empty($id)) {

    // UPDATE
    $stmt = $conn->prepare("
        UPDATE students 
        SET student_id=?, full_name=?, grade_level=?, section=? 
        WHERE id=?
    ");

    $stmt->bind_param("ssssi",
        $student_id,
        $full_name,
        $grade_level,
        $section,
        $id
    );

} else {

    // INSERT
    $stmt = $conn->prepare("
        INSERT INTO students (student_id, full_name, grade_level, section)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->bind_param("ssss",
        $student_id,
        $full_name,
        $grade_level,
        $section
    );
}

$stmt->execute();

header("Location: students.php");
exit();
?>