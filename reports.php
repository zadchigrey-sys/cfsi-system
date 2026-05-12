<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

ob_start();
?>

<h1 class="text-2xl font-bold mb-6">Reports</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- TOTAL COLLECTION -->
    <a href="report_collections.php"
       class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Collection Report
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    View all collected payments
                </p>
            </div>

            <i class="ri-money-dollar-circle-line text-4xl text-green-500"></i>
        </div>
    </a>

    <!-- OUTSTANDING -->
    <a href="report_outstanding.php"
       class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Outstanding Balances
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Students with unpaid balances
                </p>
            </div>

            <i class="ri-error-warning-line text-4xl text-red-500"></i>
        </div>
    </a>

    <!-- MONTHLY -->
    <a href="report_monthly.php"
       class="bg-white shadow rounded-xl p-6 hover:shadow-lg transition">

        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-800">
                    Monthly Collections
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Monthly income summary
                </p>
            </div>

            <i class="ri-bar-chart-box-line text-4xl text-blue-500"></i>
        </div>
    </a>

</div>

<?php
$content = ob_get_clean();
include "layout.php";
?>