<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = $_POST['id'] ?? '';
    $student_id = $_POST['student_id'];
    $name = $_POST['full_name'];
    $grade = $_POST['grade_level'];
    $section = $_POST['section'];

    // 👉 INSERT
    if (empty($id)) {

        $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, grade_level, section) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $student_id, $name, $grade, $section);

    } else {
        // 👉 UPDATE
        $stmt = $conn->prepare("UPDATE students SET student_id=?, full_name=?, grade_level=?, section=? WHERE id=?");
        $stmt->bind_param("ssssi", $student_id, $name, $grade, $section, $id);
    }

    if ($stmt->execute()) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}