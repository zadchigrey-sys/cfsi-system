<?php
session_start();
include "db.php";

$sql = "
SELECT 
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
ORDER BY p.payment_date DESC
";

$result = $conn->query($sql);

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">
    Collection Report
</h1>

<div class="bg-white shadow rounded-lg overflow-hidden">

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

<?php
$content = ob_get_clean();
include "layout.php";
?>