<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$user = $_SESSION['user'];

// ===== DASHBOARD STATS =====
$totalStudents = $conn->query("SELECT COUNT(*) as total 
    FROM students 
    WHERE deleted_at IS NULL
")->fetch_assoc()['total'];

// TOTAL COLLECTED
$totalCollected = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) AS total
    FROM payments
    WHERE deleted_at IS NULL
    AND status = 'Completed'
")->fetch_assoc()['total'] ?? 0;

// OUTSTANDING BALANCE
$outstanding = $conn->query("SELECT COALESCE(SUM(
        b.total_amount - (
            SELECT COALESCE(SUM(p.amount_paid), 0)
            FROM payments p
            WHERE p.billing_id = b.billing_id
            AND p.deleted_at IS NULL
            AND p.status = 'Completed'
        )
    ), 0) AS total
    FROM billings b
    WHERE b.deleted_at IS NULL
")->fetch_assoc()['total'] ?? 0;

// MONTHLY REVENUE
$monthlyRevenue = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) AS total
    FROM payments
    WHERE deleted_at IS NULL
    AND status = 'Completed'
    AND MONTH(payment_date) = MONTH(CURDATE())
    AND YEAR(payment_date) = YEAR(CURDATE())
")->fetch_assoc()['total'] ?? 0;

// ===== CALENDAR EVENTS =====
$events = [];

$result = $conn->query("SELECT billing_id, billing_date, due_date 
                        FROM billings 
                        WHERE deleted_at IS NULL");

while($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => 'Billing: ' . $row['billing_id'],
        'date' => $row['billing_date']
    ];

    $events[] = [
        'title' => 'Due: ' . $row['billing_id'],
        'date' => $row['due_date']
    ];
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>CFSI Dashboard</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<div class="w-64 bg-[#1a3a6b] text-white h-screen fixed left-0 top-0 p-5">
    <h2 class="text-xl font-bold mb-6">CFSI Billing System</h2>

    <a href="dashboard.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Dashboard</a>
    <a href="students.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Students</a>
    <a href="billing.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Billing</a>
    <a href="payments.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Payments</a>
    <a href="reports.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Reports</a>
    <a href="recycle_bin.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Recycle Bin</a>
    <a href="logs.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Audit Logs</a>

    <?php if ($user['role'] == 'administrator'): ?>
        <a href="users.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">User Management</a>
    <?php endif; ?>

    <a href="logout.php" class="block py-2 mt-6 bg-red-500 rounded text-center">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="flex-1 p-6 ml-64">

   <!-- HEADER -->
<div class="flex justify-between items-center mb-6">

    <!-- LEFT: SCHOOL NAME -->
    <h1 class="text-xl font-semibold text-[#1a3a6b] whitespace-nowrap">
        CHILDREN OF FATIMA SCHOOL OF STO. TOMAS, INC.
    </h1>

    <!-- RIGHT: SEARCH + PROFILE -->
    <div class="flex items-center gap-4">

        <!-- SEARCH BAR -->
        <form action="search.php" method="GET" class="relative">
    <!-- ICON -->
    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>

    <!-- INPUT -->
    <input 
        type="text" 
        name="search" 
        placeholder="Search content..."
        class="pl-12 pr-4 py-2.5 w-64 
               bg-white border border-gray-300 
               rounded-full shadow-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               text-sm transition"
    >
</form>

        <!-- PROFILE -->
        <div class="flex items-center gap-2 cursor-pointer">

            <!-- Avatar Circle -->
            <div class="w-10 h-10 bg-[#1a3a6b] text-white flex items-center justify-center rounded-full font-bold">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>

            <!-- Optional Name (small) -->
            <span class="text-sm font-medium text-gray-700 hidden md:block">
                <?php echo $user['name']; ?>
            </span>

        </div>

    </div>

</div>

<!-- SUMMARY CARDS (REPLACE THE ENTIRE GRID) -->
<div class="grid grid-cols-4 gap-4">
    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-gray-500 text-sm">Total Students</h3>
        <p class="text-2xl font-bold"><?php echo number_format($totalStudents); ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-gray-500 text-sm">Fees Collected</h3>
        <p class="text-2xl font-bold text-green-600">₱<?php echo number_format($totalCollected, 2); ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-gray-500 text-sm">Outstanding</h3>
        <p class="text-2xl font-bold text-red-600">₱<?php echo number_format($outstanding, 2); ?></p>
    </div>
    <div class="bg-white p-5 rounded-xl shadow">
        <h3 class="text-gray-500 text-sm">Monthly Revenue</h3>
        <p class="text-2xl font-bold text-blue-600">₱<?php echo number_format($monthlyRevenue, 2); ?></p>
    </div>
</div>

    <!-- TABS -->
<div class="mt-6 border-b flex gap-6 text-sm font-medium">

    <button onclick="showTab('students')" id="tab-students"
        class="pb-2 border-b-2 border-blue-600 text-blue-600">
        Students
    </button>

    <button onclick="showTab('billing')" id="tab-billing"
        class="pb-2 text-gray-500 hover:text-blue-600">
        Billing
    </button>

</div>

<!-- STUDENTS VIEW -->
<div id="students" class="mt-4">
<div class="flex justify-between items-center mb-4">

    <!-- LEFT: TITLE -->
    <div>
        <h2 class="text-lg font-semibold">Students</h2>
        <p class="text-sm text-gray-400">Quick view</p>
    </div>

    <!-- RIGHT: SEARCH + BUTTON -->
    <div class="flex items-center gap-3">

       <!-- RIGHT: SEARCH ONLY -->
<form id="quickSearchForm" method="GET" action="dashboard.php" class="relative">
    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>

    <input 
        type="text" 
        name="search" 
        placeholder="Search students..."
        class="pl-12 pr-4 py-2.5 w-64 
               bg-white border border-gray-300 
               rounded-full shadow-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               text-sm transition"
    >
</form>

    </div>
    
</div>

<div class="flex gap-2 mb-3">

    <!-- STATUS -->
    <select class="border rounded px-3 py-1 text-sm" required>
        <option value="" disabled selected hidden>Status</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>

    <!-- GRADE -->
    <select class="border rounded px-3 py-1 text-sm" required>
        <option value="" disabled selected hidden>Grade level</option>
        <option>Nursery</option>
        <option>Kinder</option>
        <option>Grade 1</option>
        <option>Grade 2</option>
        <option>Grade 3</option>
        <option>Grade 4</option>
        <option>Grade 5</option>
        <option>Grade 6</option>
        <option>Grade 7</option>
        <option>Grade 8</option>
        <option>Grade 9</option>
        <option>Grade 10</option>
        <option>Grade 11</option>
        <option>Grade 12</option>
    </select>

</div>

    <?php
    $search = $_GET['search'] ?? '';

if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM students 
        WHERE deleted_at IS NULL 
        AND (full_name LIKE ? OR student_id LIKE ?)
        ORDER BY id DESC
        LIMIT 5
    ");
    $like = "%$search%";
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $students = $stmt->get_result();
} else {
    $students = $conn->query("SELECT * FROM students 
        WHERE deleted_at IS NULL 
        ORDER BY id DESC 
        LIMIT 5
    ");
};
    while($row = $students->fetch_assoc()):
    ?>

    <div class="bg-white p-4 rounded-xl shadow mb-3">

        <h3 class="font-semibold"><?php echo $row['full_name']; ?></h3>
        <p class="text-sm text-gray-500">ID: <?php echo $row['student_id']; ?></p>

        <div class="mt-2 text-sm">
            <span class="bg-green-100 text-green-600 px-2 py-1 rounded">
                Active
            </span>
        </div>

        <div class="mt-3 text-sm text-gray-600">
            Grade: <?php echo $row['grade_level']; ?> |
            Section: <?php echo $row['section']; ?>
        </div>

    </div>

    <?php endwhile; ?>
    <a href="students.php"
class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded text-sm">
View All Students
</a>
</div>

<!-- BILLING VIEW -->
<div id="billing" class="mt-4 hidden">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-4">

        <div>
            <h2 class="text-lg font-semibold">Billing</h2>
            <p class="text-sm text-gray-400">Quick view</p>
        </div>

        <!-- SEARCH BAR -->
        <form method="GET" class="relative">
            <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
            <input 
                type="text" 
                name="billing_search"
                placeholder="Search billing..."
                class="pl-12 pr-4 py-2.5 w-64 
                       bg-white border border-gray-300 
                       rounded-full shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500
                       text-sm">
        </form>

    </div>

    <!-- FILTERS -->
    <div class="flex gap-2 mb-3">

        <select class="border rounded px-3 py-1 text-sm">
            <option value="" disabled selected hidden>Status</option>
            <option>Paid</option>
            <option>Unpaid</option>
            <option>Pending</option>
        </select>

        <select class="border rounded px-3 py-1 text-sm">
            <option value="" disabled selected hidden>Grade</option>
            <option>Nursery</option>
            <option>Kinder</option>
            <option>Grade 1</option>
            <option>Grade 2</option>
            <option>Grade 3</option>
            <option>Grade 4</option>
            <option>Grade 5</option>
            <option>Grade 6</option>
            <option>Grade 7</option>
            <option>Grade 8</option>
            <option>Grade 9</option>
            <option>Grade 10</option>
            <option>Grade 11</option>
            <option>Grade 12</option>
        </select>

    </div>

    <!-- BILLING LIST -->
    <?php
    $billings = $conn->query("SELECT * FROM billings 
        WHERE deleted_at IS NULL 
        ORDER BY created_at DESC
        LIMIT 5
    ");
    while($row = $billings->fetch_assoc()):
    ?>

    <div class="bg-white p-4 rounded-xl shadow mb-3">

        <h3 class="font-semibold"><?php echo $row['billing_id']; ?></h3>

        <div class="mt-2">
            <span class="px-2 py-1 rounded text-sm
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
        </div>

        <div class="text-sm text-gray-600 mt-2">
            Billing: <?php echo $row['billing_date']; ?><br>
            Due: <?php echo $row['due_date']; ?>
        </div>

        <div class="mt-2 font-bold text-blue-600">
            ₱<?php echo number_format($row['total_amount'],2); ?>
        </div>

    </div>

    <?php endwhile; ?>

    <!-- VIEW ALL -->
    <a href="billing.php"
    class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded text-sm">
    View All Billing
    </a>

</div> <!-- ✅ CORRECT closing -->

<!-- CALENDAR -->
<div class="mt-6">
    <h2 class="text-lg font-semibold mb-2">Calendar</h2>

    <div id="calendar" class="bg-white p-4 rounded-xl shadow"></div>
</div>

<!-- BILLING MODAL -->
<div id="billingModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-xl shadow-2xl p-6 relative max-h-[90vh] overflow-y-auto">

        <button onclick="closeBillingModal()" 
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold transition-colors">
            &times;
        </button>

        <h2 id="billingTitle" class="text-xl font-bold mb-4 text-gray-800">Add Billing</h2>

        <form id="billingForm" method="POST" action="save_billing.php" class="space-y-4">

            <input type="hidden" id="billing_id_hidden" name="id">    

            <input type="text" id="billing_id" name="billing_id"
                placeholder="Billing ID"
                class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>

            <select id="status" name="status" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Status</option>
                <option value="Paid">Paid</option>
                <option value="Unpaid">Unpaid</option>
                <option value="Pending">Pending</option>
            </select>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Billing Date</label>
                <input type="date" id="billing_date" name="billing_date"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                <input type="date" id="due_date" name="due_date"
                    class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <input type="text" id="fee_type" name="fee_type"
                placeholder="Fee Name (e.g., Tuition, Miscellaneous)"
                class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>

            <input type="number" id="total_amount" name="total_amount" step="0.01"
                placeholder="0.00"
                class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeBillingModal()"
                    class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                    Cancel
                </button>

                <button type="submit" id="billingSubmit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Save Billing
                </button>
            </div>

        </form>

    </div>
</div>

<script>
const events = <?php echo json_encode($events); ?>;
</script>

<script src="script.js"></script>

</body>
</html>