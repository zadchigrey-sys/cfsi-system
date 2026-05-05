<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo $pageTitle ?? "Dashboard"; ?></title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

<!-- SIDEBAR (FIXED) -->
<div class="w-64 bg-[#1a3a6b] text-white h-screen fixed left-0 top-0 p-5">
    <h2 class="text-xl font-bold mb-6">CFSI Billing System</h2>

    <a href="dashboard.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Dashboard</a>
    <a href="students.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Students</a>
    <a href="billing.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Billing</a>
    <a href="payments.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Payments</a>
    <a href="reports.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Reports</a>
    <a href="recycle_bin.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Recycle Bin</a>
    <a href="logs.php" class="block py-2 hover:bg-[#2a5298] rounded px-2">Audit Logs</a>

    <?php if (!empty($user) && $user['role'] === 'administrator'): ?>
        <a href="#" class="block py-2 hover:bg-[#2a5298] rounded px-2">Users</a>
    <?php endif; ?>

    <a href="logout.php" class="block py-2 mt-6 bg-red-500 rounded text-center">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="flex-1 ml-64 p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-semibold text-[#1a3a6b]">
            CHILDREN OF FATIMA SCHOOL OF STO. TOMAS, INC.
        </h1>

        <div class="flex items-center gap-4">

            <!-- GLOBAL SEARCH -->
            <form action="search.php" method="GET" class="relative">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input 
                    type="text" 
                    name="search"
                    placeholder="Search system..."
                    class="pl-12 pr-4 py-2.5 w-64 bg-white border border-gray-300 rounded-full shadow-sm
                           focus:ring-2 focus:ring-blue-500 text-sm"
                >
            </form>

            <div class="w-10 h-10 bg-[#1a3a6b] text-white flex items-center justify-center rounded-full">
                <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
            </div>

        </div>
    </div>

    <!-- PAGE CONTENT -->
    <?php echo $content; ?>

</div>
<script src="script.js"></script>
</body>
</html>