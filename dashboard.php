<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];

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
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR -->
<div class="w-64 bg-[#1a3a6b] text-white min-h-screen p-5">
    <h2 class="text-xl font-bold mb-6">CFSI Billing System</h2>

    <a href="dashboard.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Dashboard</a>
    <a href="students.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Students List</a>
    <a href="billing.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Billing</a>
    <a href="payments.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Payments</a>
    <a href="reports.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Reports</a>

    <?php if ($user['role'] == 'administrator'): ?>
        <a href="#" class="block py-2 hover:bg-[#2a5298] rounded px-2">Users</a>
    <?php endif; ?>

    <a href="logout.php" class="block py-2 mt-6 bg-red-500 rounded text-center">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="flex-1 p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">

    <!-- LEFT: SCHOOL NAME + SEARCH -->
    <div class="flex items-center gap-4">

        <!-- SCHOOL NAME -->
        <h1 class="text-2xl font-bold text-[#1a3a6b]">
            CHILDREN OF FATIMA SCHOOL OF STO. TOMAS, INC.
        </h1>

        <!-- SEARCH BAR -->
        <form action="students.php" method="GET" class="relative">
            <input 
                type="text" 
                name="search" 
                placeholder="Search student..." 
                class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            <!-- ICON -->
            <i class="ri-search-line absolute left-3 top-2.5 text-gray-500"></i>
        </form>

    </div>

    <!-- RIGHT: USER -->
    <p>Welcome, <?php echo $user['name']; ?></p>

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

    <!-- QUICK ACTION BUTTONS -->
<div class="mt-6 flex gap-3">

    <a href="add_student.php"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
       Add Student
    </a>

    <a href="#"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
       Create Billing
    </a>

    <a href="#"
       class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
       Record Payment
    </a>

</div>

    

</div>

</body>
</html>