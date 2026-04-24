<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST['student_id'];
    $name = $_POST['full_name'];
    $grade = $_POST['grade_level'];
    $section = $_POST['section'];

    $stmt = $conn->prepare("INSERT INTO students (student_id, full_name, grade_level, section) VALUES (?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("ssss", $student_id, $name, $grade, $section);

        if ($stmt->execute()) {
            header("Location: students.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Prepare failed: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6 bg-gray-100">

<h2 class="text-xl font-bold mb-4">Add Student</h2>

<form method="POST" class="space-y-4 bg-white p-6 rounded shadow w-96">

    <input type="text" name="student_id" placeholder="Student ID" required class="w-full border p-2 rounded">

    <input type="text" name="full_name" placeholder="Full Name" required class="w-full border p-2 rounded">

    <input type="text" name="grade_level" placeholder="Grade Level" required class="w-full border p-2 rounded">

    <input type="text" name="section" placeholder="Section" required class="w-full border p-2 rounded">

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Save Student
    </button>

</form>

</body>
</html>