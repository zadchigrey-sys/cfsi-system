<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];

// Stats
$totalCollected = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) as total FROM payments WHERE deleted_at IS NULL")->fetch_assoc()['total'] ?? 0;
$outstanding = $conn->query("
    SELECT COALESCE(SUM(remaining_balance), 0) as total 
    FROM billings 
    WHERE deleted_at IS NULL
")->fetch_assoc()['total'] ?? 0;
$todayPayments = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) as total FROM payments WHERE DATE(payment_date) = CURDATE() AND deleted_at IS NULL")->fetch_assoc()['total'] ?? 0;
$totalPayments = $conn->query("SELECT COUNT(*) as total FROM payments WHERE deleted_at IS NULL")->fetch_assoc()['total'] ?? 0;

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$sql = "SELECT p.*, s.full_name, s.student_id as s_id, b.billing_id, b.fee_type 
        FROM payments p 
        JOIN students s ON p.student_id = s.student_id 
        JOIN billings b ON p.billing_id = b.billing_id 
        WHERE p.deleted_at IS NULL";

$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (p.payment_id LIKE ? OR s.full_name LIKE ? OR p.reference_no LIKE ?)";
    $like = "%$search%";
    $params[] = $like; $params[] = $like; $params[] = $like;
    $types .= "sss";
}

if (!empty($status_filter)) {
    $sql .= " AND p.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql .= " ORDER BY p.payment_date DESC LIMIT 50";

if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $payments = $stmt->get_result();
} else {
    $payments = $conn->query($sql);
}

ob_start(); // 👈 SAME AS YOUR OTHER PAGES
?>

<h1 class="text-2xl font-bold mb-6 text-gray-900">Payments Management</h1>

<!-- STATS CARDS - SAME STYLE AS DASHBOARD -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Collected</h3>
        <p class="text-3xl font-bold text-green-600 mt-2">₱<?php echo number_format($totalCollected, 2); ?></p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium">Today's Payments</h3>
        <p class="text-3xl font-bold text-blue-600 mt-2">₱<?php echo number_format($todayPayments, 2); ?></p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition-shadow">
        <h3 class="text-gray-500 text-sm font-medium">Total Transactions</h3>
        <p class="text-3xl font-bold text-purple-600 mt-2"><?php echo number_format($totalPayments); ?></p>
    </div>
</div>

<!-- CONTROLS -->
<div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mb-6 bg-white p-6 rounded-xl shadow">
    <div>
        <h2 class="text-xl font-semibold text-gray-900">Payment Records</h2>
        <p class="text-sm text-gray-500"><?php echo $payments->num_rows ?? 0; ?> payments found</p>
    </div>
    
    <div class="flex flex-wrap gap-3 items-center">
        <!-- Search -->
        <form method="GET" class="relative">
            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                   placeholder="Search by payment ID, student name..." 
                   class="pl-12 pr-4 py-2.5 w-72 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
        </form>
        
        <!-- Filter -->
        <select name="status" onchange="this.form.submit()" class="border border-gray-300 px-4 py-2.5 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="Completed" <?php echo ($status_filter=='Completed')?'selected':'';?>> Completed</option>
            <option value="Pending" <?php echo ($status_filter=='Pending')?'selected':'';?>> Pending</option>
            <option value="Failed" <?php echo ($status_filter=='Failed')?'selected':'';?>> Failed</option>
        </select>
        
        <!-- Add Button -->
        <button type="button" onclick="openPaymentModal()" class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700 font-medium shadow transition-all">
            + Record Payment
        </button>
    </div>
</div>

<!-- TABLE - SAME STYLE AS YOUR OTHER PAGES -->
<div class="bg-white shadow rounded-lg overflow-hidden">
    <?php if(($payments->num_rows ?? 0) > 0): ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-[#1a3a6b] text-white">
                <tr>
                    <th class="p-4 text-left">Payment ID</th>
                    <th class="p-4 text-left">Student</th>
                    <th class="p-4 text-left">Billing</th>
                    <th class="p-4 text-right">Amount</th>
                    <th class="p-4 text-left">Method</th>
                    <th class="p-4 text-left">Date</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php while($row = $payments->fetch_assoc()): ?>
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-mono font-semibold"><?php echo htmlspecialchars($row['payment_id']); ?></td>
                    <td class="p-4">
                        <div class="font-medium"><?php echo htmlspecialchars($row['full_name']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo $row['s_id']; ?></div>
                    </td>
                    <td class="p-4">
                        <div><?php echo htmlspecialchars($row['billing_id']); ?></div>
                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($row['fee_type']); ?></div>
                    </td>
                    <td class="p-4 text-right font-bold text-green-600">
                        ₱<?php echo number_format($row['amount_paid'], 2); ?>
                    </td>
                    <td class="p-4">
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm rounded-full">
                            <?php echo htmlspecialchars($row['payment_method']); ?>
                        </span>
                    </td>
                    <td class="p-4 text-sm"><?php echo date('M j, Y', strtotime($row['payment_date'])); ?></td>
                    <td class="p-4">
                        <?php 
                        $statusClass = [
                            'Completed' => 'bg-green-100 text-green-700',
                            'Pending' => 'bg-yellow-100 text-yellow-700', 
                            'Failed' => 'bg-red-100 text-red-700',
                            'Refunded' => 'bg-gray-100 text-gray-700'
                        ];
                        $status = $statusClass[$row['status']] ?? 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="px-3 py-1 <?php echo $status; ?> text-sm font-medium rounded-full">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="flex gap-2">
                            <button type="button"  onclick="openEditPayment(
    '<?php echo $row['id']; ?>',
    '<?php echo $row['payment_id']; ?>',
    '<?php echo $row['student_id']; ?>',
    '<?php echo $row['billing_id']; ?>',
    '<?php echo $row['amount_paid']; ?>',
    '<?php echo $row['payment_method']; ?>',
    '<?php echo $row['payment_date']; ?>',
    '<?php echo $row['status']; ?>'
)" 
class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600">
Edit
</button>
                            <a href="delete_payment.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Delete this payment record?')"
                               class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-12">
        <i class="ri-money-dollar-circle-line text-6xl text-gray-300 mb-4 block mx-auto"></i>
        <h3 class="text-xl font-semibold text-gray-500 mb-2">No Payments Yet</h3>
        <p class="text-gray-400 mb-6">Record your first payment to get started.</p>
        <button  type="button" onclick="openPaymentModal()" class="bg-green-600 text-white px-6 py-2.5 rounded-lg font-medium hover:bg-green-700">
            Record First Payment
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- PAYMENT MODAL -->
<div id="paymentModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closePaymentModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        
        <h2 id="paymentTitle" class="text-xl font-bold mb-6 text-gray-800">Record Payment</h2>
        
        <form action="save_payment.php" method="POST" class="space-y-4" id="paymentForm">
>
            <input type="hidden" id="payment_id_hidden" name="id">
            
            <input type="text" id="payment_id" name="payment_id" placeholder="Payment ID (auto)" 
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500" readonly>
            
            <select name="student_id" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Student</option>
                <?php
                $students = $conn->query("SELECT student_id, full_name FROM students WHERE deleted_at IS NULL ORDER BY full_name");
                while($s = $students->fetch_assoc()): ?>
                    <option value="<?php echo $s['student_id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <select name="billing_id" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Select Billing</option>
                <?php
                $billings = $conn->query("SELECT billing_id, fee_type FROM billings WHERE deleted_at IS NULL ORDER BY billing_date DESC");
                while($b = $billings->fetch_assoc()): ?>
                    <option value="<?php echo $b['billing_id']; ?>"><?php echo htmlspecialchars($b['billing_id'] . ' - ' . $b['fee_type']); ?></option>
                <?php endwhile; ?>
            </select>
            
            <input type="number" id="amount_paid" name="amount_paid" step="0.01" min="0" required 
                   placeholder="Amount Paid" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-green-500 text-right font-mono">
            
            <select name="payment_method" required class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="Cash"> Cash</option>
                <option value="Check"> Check</option>
                <option value="GCash"> GCash</option>
                <option value="Online"> PayPal</option>
            </select>
            
            <input type="date" name="payment_date" required value="<?php echo date('Y-m-d'); ?>" 
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500">
            
            <select name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="Completed"> Completed</option>
                <option value="Pending"> Pending</option>
            </select>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closePaymentModal()" 
                        class="flex-1 px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-medium">
                    Cancel
                </button>
                <button 
                type="submit"
                id="paymentSubmitBtn"
                class="flex-1 px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium shadow">
                Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>


<?php
$content = ob_get_clean();
include "layout.php";
?>
