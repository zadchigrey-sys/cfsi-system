<?php
include "db.php";

ob_start();
?>

<h1 class="text-2xl font-bold mb-4">Recycle Bin</h1>

<!-- ================= STUDENTS ================= -->
<h2 class="text-lg font-semibold mb-3">Deleted Students</h2>

<table class="w-full bg-white shadow rounded mb-6">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">Student ID</th>
            <th class="p-3">Name</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $students = $conn->query("SELECT * FROM students WHERE deleted_at IS NOT NULL");

    while($row = $students->fetch_assoc()):
    ?>
    <tr class="border-b text-center hover:bg-gray-50">
        <td class="p-3"><?php echo $row['student_id']; ?></td>
        <td class="p-3"><?php echo $row['full_name']; ?></td>

        <td class="p-3 space-x-2">
            <a href="restore_student.php?id=<?php echo $row['id']; ?>"
               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
               Restore
            </a>

            <a href="force_delete_student.php?id=<?php echo $row['id']; ?>"
               onclick="return confirm('Delete permanently?')"
               class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
               Permanently delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>


<!-- ================= BILLINGS ================= -->
<h2 class="text-lg font-semibold mb-3">Deleted Billings</h2>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">Billing ID</th>
            <th class="p-3">Fee Name</th>
            <th class="p-3">Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $billings = $conn->query("SELECT * FROM billings WHERE deleted_at IS NOT NULL");

    while($row = $billings->fetch_assoc()):
    ?>
    <tr class="border-b text-center hover:bg-gray-50">
        <td class="p-3"><?php echo $row['billing_id']; ?></td>
        <td class="p-3"><?php echo $row['fee_type']; ?></td>

        <td class="p-3 space-x-2">
            <a href="restore_billing.php?id=<?php echo $row['id']; ?>"
               class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">
               Restore
            </a>

            <a href="force_delete_billing.php?id=<?php echo $row['id']; ?>"
               onclick="return confirm('Delete permanently?')"
               class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
               Permanently delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include "layout.php";
?>