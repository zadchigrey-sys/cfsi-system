<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$student_id = $_GET['student_id'] ?? '';

if (!$student_id) {
    die("Student not found.");
}

// ✅ Get student info
$stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// ✅ Get payments
$stmt = $conn->prepare("SELECT p.*, b.fee_type, b.total_amount
    FROM payments p
    JOIN billings b ON p.billing_id = b.billing_id
    WHERE p.student_id = ? AND p.deleted_at IS NULL
    ORDER BY p.payment_date DESC
");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$payments = $stmt->get_result();

// ✅ Total paid
$stmt = $conn->prepare("SELECT COALESCE(SUM(amount_paid),0) as total_paid
    FROM payments
    WHERE student_id = ? AND deleted_at IS NULL
");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$total_paid = $stmt->get_result()->fetch_assoc()['total_paid'];

$stmt = $conn->prepare("SELECT COALESCE(SUM(b.remaining_balance),0) AS total_balance
    FROM billings b
    WHERE b.student_id = ? AND b.deleted_at IS NULL
");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$total_balance = $stmt->get_result()->fetch_assoc()['total_balance'];

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">Payment History</h1>

<!-- STUDENT INFO -->
<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-semibold"><?php echo $student['full_name']; ?></h2>
    <p class="text-gray-500">Student ID: <?php echo $student['student_id']; ?></p>
</div>

<!-- SUMMARY -->
<div class="bg-green-100 p-4 rounded mb-6">
    <h3 class="text-lg font-semibold">Total Paid</h3>
    <p class="text-2xl font-bold text-green-700">₱<?php echo number_format($total_paid,2); ?></p>
</div>

<!-- PAYMENT TABLE -->
<div class="bg-white shadow rounded overflow-hidden">
<table class="w-full">
    <thead class="bg-[#1a3a6b] text-white">
        <tr>
            <th class="p-3 text-left">Payment ID</th>
            <th class="p-3 text-left">Billing</th>
            <th class="p-3 text-right">Amount</th>
            <th class="p-3 text-left">Method</th>
            <th class="p-3 text-left">Date</th>
            <th class="p-3 text-left">Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $payments->fetch_assoc()): ?>
        <tr class="border-b hover:bg-gray-50">
            <td class="p-3"><?php echo $row['payment_id']; ?></td>
            <td class="p-3">
                <?php echo $row['billing_id']; ?><br>
                <span class="text-sm text-gray-500"><?php echo $row['fee_type']; ?></span>
            </td>
            <td class="p-3 text-right font-bold text-green-600">
                ₱<?php echo number_format($row['amount_paid'],2); ?>
            </td>
            <td class="p-3"><?php echo $row['payment_method']; ?></td>
            <td class="p-3"><?php echo date('M j, Y', strtotime($row['payment_date'])); ?></td>
            <td class="p-3"><?php echo $row['status']; ?></td>
        </tr>
        <?php endwhile; ?>

    </tbody>

</table>
<!-- MOVE HERE -->
<div class="bg-red-100 p-4 rounded mb-6">
    <h3 class="text-lg font-semibold">Total Remaining Balance</h3>
    <p class="text-2xl font-bold text-red-700">
        ₱<?php echo number_format($total_balance,2); ?>
    </p>
</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>