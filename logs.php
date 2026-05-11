<?php
include "db.php";

$result = $conn->query("SELECT * FROM audit_logs ORDER BY created_at DESC");

ob_start();
?>

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Audit Logs</h1>

    <?php if(isset($_GET['cleared'])): ?>
<div class="mb-4 bg-green-100 text-green-700 px-4 py-3 rounded">
    Audit logs cleared successfully.
</div>
<?php endif; ?>

    <!-- CLEAR LOGS BUTTON -->
    <a href="clear_logs.php"
       onclick="return confirm('Are you sure you want to clear all audit logs?')"
       class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">
        Clear History
    </a>
</div>

<table class="w-full bg-white shadow rounded overflow-hidden">
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
        <td class="p-3 font-semibold">
            <?php echo htmlspecialchars($row['action']); ?>
        </td>

        <td class="p-3">
            <?php echo htmlspecialchars($row['table_name']); ?>
        </td>

        <td class="p-3">
            <?php echo htmlspecialchars($row['record_id']); ?>
        </td>

        <td class="p-3">
            <?php echo htmlspecialchars($row['user_name']); ?>
        </td>

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