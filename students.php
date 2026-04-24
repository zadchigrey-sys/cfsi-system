<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Fetch students
$result = $conn->query("SELECT * FROM students");
?>

<!DOCTYPE html>
<html>
<head>
<title>Students</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-4">Students List</h1>

<a href="add_student.php" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
    + Add Student
</a>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">Student ID</th>
            <th class="p-3">Full Name</th>
            <th class="p-3">Grade</th>
            <th class="p-3">Section</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr class="border-b text-center">
            <td class="p-3"><?php echo $row['student_id']; ?></td>
            <td class="p-3"><?php echo $row['full_name']; ?></td>
            <td class="p-3"><?php echo $row['grade_level']; ?></td>
            <td class="p-3"><?php echo $row['section']; ?></td>

            <td class="p-3 space-x-2">
                <a href="edit_student.php?id=<?php echo $row['id']; ?>"
                   class="bg-yellow-500 text-white px-3 py-1 rounded">
                   Edit
                </a>

                <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                   onclick="return confirm('Delete this student?')"
                   class="bg-red-600 text-white px-3 py-1 rounded">
                   Delete
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="5" class="p-4 text-center">No students found.</td>
</tr>
<?php endif; ?>
    </tbody>
</table>

</body>
</html>