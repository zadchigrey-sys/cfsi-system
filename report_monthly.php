<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// =========================
// MONTHLY COLLECTION QUERY
// =========================
$sql = "SELECT
    DATE_FORMAT(payment_date, '%M %Y') AS month_name,
    DATE_FORMAT(payment_date, '%Y-%m') AS month_sort,
    COUNT(*) AS total_transactions,
    COALESCE(SUM(amount_paid), 0) AS total_collections
FROM payments
WHERE deleted_at IS NULL
AND status = 'Completed'
GROUP BY month_sort
ORDER BY month_sort DESC";

$result = $conn->query($sql);

// =========================
// START OUTPUT BUFFER
// =========================
$grandTotal = 0;

$tempResult = $conn->query($sql);

while($tempRow = $tempResult->fetch_assoc()) {
    $grandTotal += $tempRow['total_collections'];
}

$result = $conn->query($sql);

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">
    Monthly Collection Report
</h1>

    <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 mb-4">

    <h2 class="text-lg font-bold text-blue-700">
        Grand Total Collections
    </h2>

    <p class="text-3xl font-bold text-blue-800">
        ₱<?php echo number_format($grandTotal ?? 0, 2); ?>
    </p>

</div>

    <div class="mb-4">
    <button onclick="window.print()"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

        Print Report
    </button>
</div>

    <input type="text"
       id="searchInput"
       placeholder="Search student..."
       class="border p-2 rounded w-full mb-4">

<div class="bg-white shadow rounded-lg overflow-hidden">
     <div class="overflow-x-auto">
    <table class="w-full">

        <thead class="bg-[#1a3a6b] text-white">
            <tr>
                <th class="p-4 text-left">Month</th>
                <th class="p-4 text-center">Transactions</th>
                <th class="p-4 text-right">Total Collections</th>
            </tr>
        </thead>

        <tbody>

        <?php
        $grandTotal = 0;
        ?>

        <?php while($row = $result->fetch_assoc()): ?>

        <?php
        $grandTotal += $row['total_collections'];
        ?>

        <tr class="border-b hover:bg-gray-50">

            <td class="p-4 font-semibold">
                <?php echo $row['month_name']; ?>
            </td>

            <td class="p-4 text-center">
                <?php echo number_format($row['total_transactions']); ?>
            </td>

            <td class="p-4 text-right font-bold text-green-600">
                ₱<?php echo number_format($row['total_collections'], 2); ?>
            </td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</div>
        </div>
<?php
$content = ob_get_clean();
include "layout.php";
?>