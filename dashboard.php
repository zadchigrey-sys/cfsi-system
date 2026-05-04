<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
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

// 👇 ADD THIS HERE
$result = $conn->query("SELECT COUNT(*) as total FROM students");
$totalStudents = $result->fetch_assoc()['total'];
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

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
        <a href="#" class="block py-2 hover:bg-[#2a5298] rounded px-2">Users</a>
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

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-4 gap-4">

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="text-gray-500">Total Students</h3>
            <p class="text-2xl font-bold"><?php echo $totalStudents; ?></p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="text-gray-500">Fees Collected</h3>
            <p class="text-2xl font-bold">₱0</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="text-gray-500">Outstanding</h3>
            <p class="text-2xl font-bold">₱0</p>
        </div>

        <div class="bg-white p-5 rounded-xl shadow">
            <h3 class="text-gray-500">Monthly Revenue</h3>
            <p class="text-2xl font-bold">₱0</p>
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

        <!-- SEARCH BAR -->
       <form id="quickSearchForm" method="GET" class="relative">
    <!-- ICON -->
    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>

    <!-- INPUT -->
    <input 
        type="text" 
        name="search" 
        placeholder="Search students or bills..."
        class="pl-12 pr-4 py-2.5 w-64 
               bg-white border border-gray-300 
               rounded-full shadow-sm
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
               text-sm transition"
    >
</form>

        <!-- ADD BUTTON -->
        <a href="add_student.php"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
           + Add student
        </a>

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
    $students = $conn->query("
    SELECT * FROM students 
    WHERE deleted_at IS NULL 
    ORDER BY id DESC 
    LIMIT 5
");
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
</div>

<!-- BILLING VIEW -->
<div id="billing" class="mt-4 hidden">


<div class="flex justify-between items-center mb-4">
    <div>
        <h2 class="text-lg font-semibold">Billing</h2>
        <p class="text-sm text-gray-400">Quick view</p>
    </div>
</div>

<div class="flex gap-2 mb-3">

    <!-- STATUS -->
    <select class="border rounded px-3 py-1 text-sm">
        <option value="" disabled selected hidden>Status</option>
        <option>Paid</option>
        <option>Unpaid</option>
        <option>Pending</option>
    </select>

    <!-- GRADE -->
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
<?php
$billings = $conn->query("
    SELECT * FROM billings 
    WHERE deleted_at IS NULL 
    ORDER BY billing_date DESC 
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

<a href="billing.php"
class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded text-sm">
View All Billing
</a>
</div>

<!-- CALENDAR -->
<div class="mt-6">
    <h2 class="text-lg font-semibold mb-2">Calendar</h2>

    <div id="calendar" class="bg-white p-4 rounded-xl shadow"></div>
</div>

<script>
const events = <?php echo json_encode($events); ?>;
</script>

<script src="script.js"></script>

</body>
</html>