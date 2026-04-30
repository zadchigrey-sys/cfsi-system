<?php
include "db.php";

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM students WHERE full_name LIKE ? OR student_id LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM students WHERE deleted_at IS NULL");
}

ob_start(); // START CONTENT
?>

<h1 class="text-2xl font-bold mb-4">Students</h1>

<div class="flex justify-between items-center mb-4">
    <form method="GET" class="relative">
        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
        <input 
            type="text" 
            name="search"
            placeholder="Search students..."
            class="pl-12 pr-4 py-2.5 w-64 bg-white border border-gray-300 rounded-full shadow-sm"
        >
    </form>

    <button onclick="openAddModal()" 
    class="bg-blue-600 text-white px-4 py-2 rounded">
    + Add Student
</button>
</div>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Grade</th>
            <th class="p-3">Section</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr class="border-b text-center">
            <td class="p-3"><?php echo $row['student_id']; ?></td>
            <td class="p-3"><?php echo $row['full_name']; ?></td>
            <td class="p-3"><?php echo $row['grade_level']; ?></td>
            <td class="p-3"><?php echo $row['section']; ?></td>
            <td class="p-3">
                <button 
onclick="openEditModal(
    '<?php echo $row['id']; ?>',
    '<?php echo $row['student_id']; ?>',
    '<?php echo $row['full_name']; ?>',
    '<?php echo $row['grade_level']; ?>',
    '<?php echo $row['section']; ?>'
)"
class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                <a href="delete_student.php?id=<?php echo $row['id']; ?>" 
   onclick="return confirm('Are you sure you want to delete this student?')"
   class="bg-red-500 text-white px-2 py-1 rounded">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<!-- MODAL BACKDROP -->
<div id="studentModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center">

    <!-- MODAL BOX -->
    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">

        <!-- CLOSE BUTTON -->
        <button onclick="closeModal()" 
            class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
            &times;
        </button>

        <h2 id="modalTitle" class="text-xl font-bold mb-4">Add Student</h2>

        <!-- FORM -->
        <form id="studentForm" method="POST" action="save_student.php" class="space-y-4">

            <input type="hidden" name="id" id="student_id_hidden">

            <input id="student_id" name="student_id" type="text"
                placeholder="Student ID"
                class="w-full border p-2 rounded">

            <input id="full_name" name="full_name" type="text"
                placeholder="Full Name"
                class="w-full border p-2 rounded">

            <input id="grade_level" name="grade_level" type="text"
                placeholder="Grade Level"
                class="w-full border p-2 rounded">

            <input id="section" name="section" type="text"
                placeholder="Section"
                class="w-full border p-2 rounded">

            <!-- BUTTONS -->
            <div class="flex justify-end gap-2">

                <!-- CANCEL -->
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                    Cancel
                </button>

                <!-- SUBMIT -->
                <button type="submit" id="submitBtn"
                    class="px-4 py-2 rounded text-white bg-blue-600">
                    Save
                </button>

            </div>

        </form>

    </div>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>

<script>
function openAddModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeModal() {
    const modal = document.getElementById("studentModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}
</script>