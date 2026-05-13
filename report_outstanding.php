<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT
    s.full_name,
    b.billing_id,
    b.fee_type,
    b.total_amount,
    b.amount_paid,
    b.remaining_balance,
    b.status
FROM billings b
JOIN students s ON b.student_id = s.student_id
WHERE b.remaining_balance > 0
AND b.status = 'Pending'
AND b.deleted_at IS NULL
ORDER BY b.remaining_balance DESC";

$result = $conn->query($sql);

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">
    Outstanding Balances
</h1>

    <div class="relative mb-4">
    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>

    <input type="text"
           id="searchOutstanding"
           placeholder="Search student..."
           class="pl-12 pr-4 py-2.5 w-full border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
     <div class="overflow-x-auto">
<table class="w-full">
    <thead class="bg-red-600 text-white">
        <tr>
            <th class="p-3 text-left">Student</th>
            <th class="p-3 text-left">Billing ID</th>
            <th class="p-3 text-left">Fee Type</th>
            <th class="p-3 text-right">Total</th>
            <th class="p-3 text-right">Paid</th>
            <th class="p-3 text-right">Balance</th>
        </tr>
    </thead>

    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr class="border-b hover:bg-gray-50">

        <td class="p-3 font-medium">
            <?php echo $row['full_name']; ?>
        </td>

        <td class="p-3">
            <?php echo $row['billing_id']; ?>
        </td>

        <td class="p-3">
            <?php echo $row['fee_type']; ?>
        </td>

        <td class="p-3 text-right">
            ₱<?php echo number_format($row['total_amount'], 2); ?>
        </td>

        <td class="p-3 text-right text-green-600">
            ₱<?php echo number_format($row['amount_paid'], 2); ?>
        </td>

        <td class="p-3 text-right text-red-600 font-bold">
            ₱<?php echo number_format($row['remaining_balance'], 2); ?>
        </td>

    </tr>
    <?php endwhile; ?>
    </tbody>

</table>

</div>
    </div>

<script>
document.getElementById("searchOutstanding").addEventListener("keyup", function() {

    let value = this.value.toLowerCase();

    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {

        row.style.display =
            row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";

    });

});
</script>

<?php
$content = ob_get_clean();
include "layout.php";
?>