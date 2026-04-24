<?php
session_start();
include "db.php";

// ✅ SECURITY: check login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// ✅ CHECK IF ID EXISTS IN URL
if (!isset($_GET['id'])) {
    echo "No student ID provided!";
    exit();
}

$id = $_GET['id'];

// ✅ FETCH STUDENT DATA
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// ✅ CHECK IF STUDENT EXISTS
if ($result->num_rows === 0) {
    echo "Student not found!";
    exit();
}

$student = $result->fetch_assoc();

// ✅ UPDATE STUDENT
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student_id = $_POST['student_id'];
    $name = $_POST['full_name'];
    $grade = $_POST['grade_level'];
    $section = $_POST['section'];

    $stmt = $conn->prepare("UPDATE students SET student_id=?, full_name=?, grade_level=?, section=? WHERE id=?");
    $stmt->bind_param("ssssi", $student_id, $name, $grade, $section, $id);

    if ($stmt->execute()) {
        header("Location: students.php");
        exit();
    } else {
        echo "Error updating student: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<h2 class="text-2xl font-bold mb-4 text-[#1a3a6b]">Edit Student</h2>

<form method="POST" class="bg-white p-6 rounded shadow w-96 space-y-4">

    <!-- STUDENT ID -->
    <input 
        type="text" 
        name="student_id" 
        value="<?php echo $student['student_id']; ?>" 
        placeholder="Student ID"
        required
        class="w-full border p-2 rounded"
    >

    <!-- FULL NAME -->
    <input 
        type="text" 
        name="full_name" 
        value="<?php echo $student['full_name']; ?>" 
        placeholder="Full Name"
        required
        class="w-full border p-2 rounded"
    >

    <!-- GRADE LEVEL -->
    <input 
        type="text" 
        name="grade_level" 
        value="<?php echo $student['grade_level']; ?>" 
        placeholder="Grade Level"
        required
        class="w-full border p-2 rounded"
    >

    <!-- SECTION -->
    <input 
        type="text" 
        name="section" 
        value="<?php echo $student['section']; ?>" 
        placeholder="Section"
        required
        class="w-full border p-2 rounded"
    >

    <!-- SUBMIT -->
    <button 
        type="submit" 
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full"
    >
        Update Student
    </button>

</form>

</body>
</html>