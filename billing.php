<?php
include "db.php";

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM billings WHERE billing_id LIKE ?");
    $like = "%$search%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM billings WHERE deleted_at IS NULL");
}

ob_start();
?>

<h1 class="text-2xl font-bold mb-4">Billing</h1>

<div class="flex justify-between items-center mb-4">

    <form method="GET" class="relative">
        <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
        <input 
            type="text" 
            name="search"
            placeholder="Search billing..."
            class="pl-12 pr-4 py-2.5 w-64 bg-white border rounded-full shadow-sm"
        >
    </form>
    <button onclick="openBillingModal()" 
class="bg-blue-600 text-white px-4 py-2 rounded">
+ Add Billing
</button>

</div>

<?php if (isset($_GET['deleted'])): ?>
    <div class="bg-green-100 text-green-700 p-3 rounded mb-3">
        Billing record deleted successfully.
    </div>
<?php endif; ?>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-[#1a3a6b] text-white">
    <tr>
        <th class="p-3">Billing ID</th>
        <th class="p-3">Fee Name</th>
        <th class="p-3">Status</th>
        <th class="p-3">Billing Date</th>
        <th class="p-3">Due Date</th>
        <th class="p-3">Amount</th>
        <th class="p-3">Actions</th>
    </tr>
</thead>

    <tbody>
<?php while($row = $result->fetch_assoc()): ?>
<tr class="border-b text-center">

    <td class="p-3"><?php echo $row['billing_id']; ?></td>

    <td class="p-3">
        <?php echo $row['fee_type']; ?>
    </td>

    <td class="p-3">
        <span class="px-2 py-1 rounded
        <?php
            if($row['status'] == 'Paid'){
                echo 'bg-green-100 text-green-600';
            } elseif($row['status'] == 'Pending'){
                echo 'bg-yellow-100 text-yellow-600';
            } else {
                echo 'bg-red-100 text-red-600';
            }
        ?>">
            <?php echo $row['status']; ?>
        </span>
    </td>

    <td class="p-3"><?php echo $row['billing_date']; ?></td>
    <td class="p-3"><?php echo $row['due_date']; ?></td>
    <td class="p-3">₱<?php echo number_format($row['total_amount'],2); ?></td>

    <td class="p-3">
        <button onclick="openEditBilling(
            '<?php echo $row['id']; ?>',
            '<?php echo $row['billing_id']; ?>',
            '<?php echo $row['fee_type']; ?>',
            '<?php echo $row['status']; ?>',
            '<?php echo $row['billing_date']; ?>',
            '<?php echo $row['due_date']; ?>',
            '<?php echo $row['total_amount']; ?>'
        )"
        class="bg-yellow-500 text-white px-2 py-1 rounded">
        Edit
        </button>

        <a href="delete_billing.php?id=<?php echo $row['id']; ?>"
   onclick="return confirm('Are you sure you want to delete this billing record?')"
   class="bg-red-500 text-white px-2 py-1 rounded">
   Delete
</a>
    </td>

</tr>
<?php endwhile; ?>
</tbody>
</table>

<!-- BILLING MODAL -->
<div id="billingModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center">

    <div class="bg-white w-full max-w-md rounded-xl shadow-lg p-6 relative">

        <!-- CLOSE -->
        <button onclick="closeBillingModal()" 
            class="absolute top-3 right-3 text-gray-500 text-xl">&times;</button>

        <h2 id="billingTitle" class="text-xl font-bold mb-4">Add Billing</h2>

        <form id="billingForm" method="POST" action="save_billing.php" class="space-y-4">

            <!-- Billing ID -->
    <input type="hidden" id="billing_id_hidden" name="id">    
    <input type="text" id="billing_id" name="billing_id"
    placeholder="Billing ID"
    class="w-full border p-2 rounded" required>

            <!-- Status -->
<select id="status" name="status" class="w-full border p-2 rounded" required>
    <option value="">Select Status</option>
    <option value="Paid">Paid</option>
    <option value="Unpaid">Unpaid</option>
    <option value="Pending">Pending</option>
</select>

            <!-- Billing Date -->
<div>
    <label class="block text-sm font-medium text-gray-600 mb-1">
        Billing Date
    </label>
    <input type="date" name="billing_date" id="billing_date"
        class="w-full border p-2 rounded" required>
</div>

<!-- Due Date -->
<div>
    <label class="block text-sm font-medium text-gray-600 mb-1">
        Due Date
    </label>
    <input type="date" name="due_date" id="due_date"
        class="w-full border p-2 rounded" required>
</div>
              
            <!-- TYPE OF FEE (replacement for Notes) -->
            <input type="text" id="fee_type" name="fee_type"
    placeholder="Fee Name (e.g. Tuition Fee)"
    class="w-full border p-2 rounded" required>

            <!-- Amount -->
<input type="number" id="total_amount" step="0.01" name="total_amount"
    placeholder="Amount"
    class="w-full border p-2 rounded" required>

            <!-- BUTTONS -->
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeBillingModal()"
                    class="px-4 py-2 bg-gray-200 rounded">
                    Cancel
                </button>

                <button type="submit" id="billingSubmit"
class="px-4 py-2 bg-blue-600 text-white rounded">
Save Billing
</button>
            </div>

        </form>

    </div>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>

 