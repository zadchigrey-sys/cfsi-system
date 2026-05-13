<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT 
    p.payment_id,
    s.full_name,
    b.fee_type,
    p.amount_paid,
    p.payment_method,
    p.payment_date
FROM payments p
JOIN students s ON p.student_id = s.student_id
JOIN billings b ON p.billing_id = b.billing_id
WHERE p.deleted_at IS NULL
AND p.status = 'Completed'
ORDER BY p.payment_date DESC
";

$result = $conn->query($sql);

$totalSQL = "SELECT COALESCE(SUM(amount_paid), 0) AS total
    FROM payments WHERE deleted_at IS NULL AND status = 'Completed'";

$totalResult = $conn->query($totalSQL);
$totalRow = $totalResult->fetch_assoc();
$totalCollections = $totalRow['total'] ?? 0;

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">
    Collection Report
</h1>

    <div class="bg-green-100 border border-green-300 rounded-lg p-4 mb-4">
    <h2 class="text-lg font-bold text-green-700">
        Total Collections
    </h2>

    <p class="text-3xl font-bold text-green-800">
        ₱<?php echo number_format($totalCollections, 2); ?>
    </p>
</div>

    <div class="mb-4">
    <button onclick="window.print()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

        Print Report
    </button>
</div>

    <div class="relative mb-4">
    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>

    <input type="text"
           id="searchInput"
           placeholder="Search student..."
           class="pl-12 pr-4 py-2.5 w-full border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
</div>

<div class="bg-white shadow rounded-lg overflow-hidden">
     <div class="overflow-x-auto">
<table class="w-full">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3 text-left">Payment ID</th>
            <th class="p-3 text-left">Student</th>
            <th class="p-3 text-left">Fee Type</th>
            <th class="p-3 text-right">Amount</th>
            <th class="p-3 text-left">Method</th>
            <th class="p-3 text-left">Date</th>
        </tr>
    </thead>

    <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr class="border-b hover:bg-gray-50">

        <td class="p-3 font-semibold">
            <?php echo $row['payment_id']; ?>
        </td>

        <td class="p-3">
            <?php echo $row['full_name']; ?>
        </td>

        <td class="p-3">
            <?php echo $row['fee_type']; ?>
        </td>

        <td class="p-3 text-right text-green-600 font-bold">
            ₱<?php echo number_format($row['amount_paid'], 2); ?>
        </td>

        <td class="p-3">
            <?php echo $row['payment_method']; ?>
        </td>

        <td class="p-3">
            <?php echo date('M d, Y', strtotime($row['payment_date'])); ?>
        </td>

    </tr>
    <?php endwhile; ?>
    </tbody>

</table>

</div>
    </div>

    <script>
document.getElementById("searchInput").addEventListener("keyup", function() {

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