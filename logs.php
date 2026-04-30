<?php
include "db.php";

$result = $conn->query("SELECT * FROM audit_logs ORDER BY created_at DESC");

ob_start();
?>

<h1 class="text-2xl font-bold mb-4">Audit Logs</h1>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3">Action</th>
            <th class="p-3">Table</th>
            <th class="p-3">Record ID</th>
            <th class="p-3">User</th>
            <th class="p-3">Date</th>
        </tr>
    </thead>

    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr class="border-b text-center hover:bg-gray-50">
        <td class="p-3 font-semibold"><?php echo $row['action']; ?></td>
        <td class="p-3"><?php echo $row['table_name']; ?></td>
        <td class="p-3"><?php echo $row['record_id']; ?></td>
        <td class="p-3"><?php echo $row['user_name']; ?></td>
        <td class="p-3 text-sm text-gray-500">
            <?php echo $row['created_at']; ?>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php
$content = ob_get_clean();
include "layout.php";
?>