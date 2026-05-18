<?php
session_start();
include "db.php";

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

$user = $_SESSION['user'];

// ===== DASHBOARD STATS =====
$totalStudents = $conn->query("SELECT COUNT(*) as total FROM students WHERE deleted_at IS NULL")->fetch_assoc()['total'];

$totalCollected = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) AS total FROM payments WHERE deleted_at IS NULL AND status = 'Completed'")->fetch_assoc()['total'] ?? 0;

$outstanding = $conn->query("SELECT COALESCE(SUM(b.total_amount - (SELECT COALESCE(SUM(p.amount_paid), 0) FROM payments p WHERE p.billing_id = b.billing_id AND p.deleted_at IS NULL AND p.status = 'Completed')), 0) AS total FROM billings b WHERE b.deleted_at IS NULL")->fetch_assoc()['total'] ?? 0;

$monthlyRevenue = $conn->query("SELECT COALESCE(SUM(amount_paid), 0) AS total FROM payments WHERE deleted_at IS NULL AND status = 'Completed' AND MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;

// ===== CALENDAR EVENTS =====
$events = [];
$result = $conn->query("SELECT billing_id, billing_date, due_date FROM billings WHERE deleted_at IS NULL");
while($row = $result->fetch_assoc()) {
    $events[] = ['title' => 'Billing: ' . $row['billing_id'], 'date' => $row['billing_date']];
    $events[] = ['title' => 'Due: ' . $row['billing_id'], 'date' => $row['due_date']];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CFSI · Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<script>
  tailwind.config = {
    theme: { extend: { fontFamily: { display: ["Playfair Display","serif"], sans: ["DM Sans","sans-serif"] } } }
  };
</script>
</head>

<body class="dash-body font-sans">

<!-- ===================== SIDEBAR ===================== -->
<aside class="sidebar" id="sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
         alt="CFSI" class="h-9 w-auto">
    <div class="sidebar-brand-text">
      <span class="sidebar-brand-title">CFSI</span>
      <span class="sidebar-brand-sub">Billing System</span>
    </div>
  </div>

  <!-- Nav -->
  <nav class="sidebar-nav">
    <span class="sidebar-section-label">Main Menu</span>
    <a href="dashboard.php" class="sidebar-link active">
      <i class="ri-dashboard-3-line"></i><span>Dashboard</span>
    </a>
    <a href="students.php" class="sidebar-link">
      <i class="ri-user-3-line"></i><span>Students</span>
    </a>
    <a href="billing.php" class="sidebar-link">
      <i class="ri-bill-line"></i><span>Billing</span>
    </a>
    <a href="payments.php" class="sidebar-link">
      <i class="ri-secure-payment-line"></i><span>Payments</span>
    </a>
    <a href="reports.php" class="sidebar-link">
      <i class="ri-bar-chart-2-line"></i><span>Reports</span>
    </a>

    <span class="sidebar-section-label mt-4">System</span>
    <a href="recycle_bin.php" class="sidebar-link">
      <i class="ri-delete-bin-6-line"></i><span>Recycle Bin</span>
    </a>
    <a href="logs.php" class="sidebar-link">
      <i class="ri-file-list-3-line"></i><span>Audit Logs</span>
    </a>
    <?php if ($user['role'] == 'administrator'): ?>
    <a href="users.php" class="sidebar-link">
      <i class="ri-shield-user-line"></i><span>User Management</span>
    </a>
    <?php endif; ?>
  </nav>

  <!-- Logout -->
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
      <div class="sidebar-user-info">
        <span class="sidebar-user-name"><?php echo $user['name']; ?></span>
        <span class="sidebar-user-role"><?php echo ucfirst($user['role']); ?></span>
      </div>
    </div>
    <a href="logout.php" class="sidebar-logout">
      <i class="ri-logout-box-r-line"></i>
      <span>Logout</span>
    </a>
  </div>
</aside>

<!-- ===================== MAIN CONTENT ===================== -->
<main class="dash-main">

  <!-- ── TOPBAR ── -->
  <header class="dash-topbar">
    <div>
      <h1 class="dash-page-title">Dashboard</h1>
      <p class="dash-page-sub">Children of Fatima School of Sto. Tomas, Inc.</p>
    </div>
    <div class="topbar-right">
      <!-- Search -->
      <form action="search.php" method="GET" class="topbar-search">
        <i class="ri-search-line search-icon"></i>
        <input type="text" name="search" placeholder="Search anything…" class="search-input">
      </form>
      <!-- Notification bell -->
      <button class="topbar-icon-btn">
        <i class="ri-notification-3-line"></i>
        <span class="notif-dot"></span>
      </button>
      <!-- Profile -->
      <div class="topbar-profile">
        <div class="topbar-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
        <span class="topbar-name"><?php echo $user['name']; ?></span>
        <i class="ri-arrow-down-s-line text-gray-400 text-sm"></i>
      </div>
    </div>
  </header>

  <!-- ── STAT CARDS ── -->
  <div class="stat-grid">

    <div class="stat-card stat-card--blue dash-anim" style="--delay:0.05s">
      <div class="stat-icon-wrap stat-icon--blue">
        <i class="ri-user-3-line"></i>
      </div>
      <div class="stat-info">
        <span class="stat-label">Total Students</span>
        <span class="stat-value"><?php echo number_format($totalStudents); ?></span>
        <span class="stat-badge stat-badge--blue">Enrolled</span>
      </div>
      <i class="ri-arrow-right-up-line stat-arrow"></i>
    </div>

    <div class="stat-card stat-card--green dash-anim" style="--delay:0.1s">
      <div class="stat-icon-wrap stat-icon--green">
        <i class="ri-money-peso-circle-line"></i>
      </div>
      <div class="stat-info">
        <span class="stat-label">Fees Collected</span>
        <span class="stat-value">₱<?php echo number_format($totalCollected, 2); ?></span>
        <span class="stat-badge stat-badge--green">Completed</span>
      </div>
      <i class="ri-arrow-right-up-line stat-arrow"></i>
    </div>

    <div class="stat-card stat-card--red dash-anim" style="--delay:0.15s">
      <div class="stat-icon-wrap stat-icon--red">
        <i class="ri-error-warning-line"></i>
      </div>
      <div class="stat-info">
        <span class="stat-label">Outstanding</span>
        <span class="stat-value">₱<?php echo number_format($outstanding, 2); ?></span>
        <span class="stat-badge stat-badge--red">Unpaid</span>
      </div>
      <i class="ri-arrow-right-up-line stat-arrow"></i>
    </div>

    <div class="stat-card stat-card--gold dash-anim" style="--delay:0.2s">
      <div class="stat-icon-wrap stat-icon--gold">
        <i class="ri-bar-chart-grouped-line"></i>
      </div>
      <div class="stat-info">
        <span class="stat-label">Monthly Revenue</span>
        <span class="stat-value">₱<?php echo number_format($monthlyRevenue, 2); ?></span>
        <span class="stat-badge stat-badge--gold">This Month</span>
      </div>
      <i class="ri-arrow-right-up-line stat-arrow"></i>
    </div>

  </div>

  <!-- ── TWO-COLUMN LAYOUT ── -->
  <div class="dash-columns">

    <!-- LEFT: Tabs + List -->
    <div class="dash-col-main dash-anim" style="--delay:0.25s">
      <div class="panel">

        <!-- Tab Header -->
        <div class="panel-tabs">
          <button onclick="showTab('students')" id="tab-students" class="tab-btn tab-btn--active">
            <i class="ri-user-3-line mr-1.5"></i>Students
          </button>
          <button onclick="showTab('billing')" id="tab-billing" class="tab-btn">
            <i class="ri-bill-line mr-1.5"></i>Billing
          </button>

          <!-- Spacer + search -->
          <div class="ml-auto flex items-center gap-3">
            <form id="quickSearchForm" method="GET" action="dashboard.php" class="tab-search-wrap">
              <i class="ri-search-line tab-search-icon"></i>
              <input type="text" name="search" placeholder="Search…" class="tab-search-input">
            </form>
          </div>
        </div>

        <!-- ── STUDENTS TAB ── -->
        <div id="students" class="tab-content">

          <!-- Filters -->
          <div class="filter-row">
            <select class="filter-select">
              <option value="" disabled selected hidden>Status</option>
              <option>Active</option>
              <option>Inactive</option>
            </select>
            <select class="filter-select">
              <option value="" disabled selected hidden>Grade Level</option>
              <option>Nursery</option><option>Kinder</option>
              <?php for($g=1;$g<=12;$g++) echo "<option>Grade $g</option>"; ?>
            </select>
          </div>

          <!-- Student Cards -->
          <div class="list-items">
          <?php
          $search = $_GET['search'] ?? '';
          if (!empty($search)) {
              $stmt = $conn->prepare("SELECT * FROM students WHERE deleted_at IS NULL AND (full_name LIKE ? OR student_id LIKE ?) ORDER BY id DESC LIMIT 5");
              $like = "%$search%";
              $stmt->bind_param("ss", $like, $like);
              $stmt->execute();
              $students = $stmt->get_result();
          } else {
              $students = $conn->query("SELECT * FROM students WHERE deleted_at IS NULL ORDER BY id DESC LIMIT 5");
          }
          while($row = $students->fetch_assoc()):
          ?>
          <div class="list-card">
            <div class="list-card-avatar"><?php echo strtoupper(substr($row['full_name'], 0, 1)); ?></div>
            <div class="list-card-body">
              <p class="list-card-name"><?php echo $row['full_name']; ?></p>
              <p class="list-card-meta">ID: <?php echo $row['student_id']; ?> &nbsp;·&nbsp; <?php echo $row['grade_level']; ?> &nbsp;·&nbsp; <?php echo $row['section']; ?></p>
            </div>
            <span class="status-pill status-pill--green">Active</span>
          </div>
          <?php endwhile; ?>
          </div>

          <a href="students.php" class="view-all-btn">
            View All Students <i class="ri-arrow-right-line ml-1"></i>
          </a>
        </div>

        <!-- ── BILLING TAB ── -->
        <div id="billing" class="tab-content hidden">

          <div class="filter-row">
            <select class="filter-select">
              <option value="" disabled selected hidden>Status</option>
              <option>Paid</option><option>Unpaid</option><option>Pending</option>
            </select>
            <select class="filter-select">
              <option value="" disabled selected hidden>Grade</option>
              <option>Nursery</option><option>Kinder</option>
              <?php for($g=1;$g<=12;$g++) echo "<option>Grade $g</option>"; ?>
            </select>
          </div>

          <div class="list-items">
          <?php
          $billings = $conn->query("SELECT * FROM billings WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5");
          while($row = $billings->fetch_assoc()):
            $statusClass = match($row['status']) {
              'Paid'    => 'status-pill--green',
              'Pending' => 'status-pill--yellow',
              default   => 'status-pill--red',
            };
          ?>
          <div class="list-card">
            <div class="list-card-icon"><i class="ri-bill-line"></i></div>
            <div class="list-card-body">
              <p class="list-card-name"><?php echo $row['billing_id']; ?> &nbsp;<span class="text-gray-400 font-normal text-xs">· <?php echo $row['fee_type'] ?? ''; ?></span></p>
              <p class="list-card-meta">Billed: <?php echo $row['billing_date']; ?> &nbsp;·&nbsp; Due: <?php echo $row['due_date']; ?></p>
            </div>
            <div class="text-right shrink-0">
              <p class="billing-amount">₱<?php echo number_format($row['total_amount'],2); ?></p>
              <span class="status-pill <?php echo $statusClass; ?>"><?php echo $row['status']; ?></span>
            </div>
          </div>
          <?php endwhile; ?>
          </div>

          <a href="billing.php" class="view-all-btn">
            View All Billing <i class="ri-arrow-right-line ml-1"></i>
          </a>
        </div>

      </div>
    </div>

    <!-- RIGHT: Calendar -->
    <div class="dash-col-side dash-anim" style="--delay:0.3s">
      <div class="panel">
        <div class="panel-header">
          <span class="panel-title"><i class="ri-calendar-event-line mr-2 text-[#2a5298]"></i>Billing Calendar</span>
          <span class="panel-subtitle">Click a date to add billing</span>
        </div>
        <div id="calendar" class="dash-calendar"></div>
      </div>
    </div>

  </div><!-- end dash-columns -->
</main>

<!-- ===================== BILLING MODAL ===================== -->
<div id="billingModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
  <div class="modal-card w-full max-w-lg relative max-h-[92vh] overflow-y-auto">

    <!-- Header -->
    <div class="modal-header">
      <div class="modal-logo-wrap">
        <i class="ri-bill-line text-[#2a5298] text-2xl"></i>
      </div>
      <h2 id="billingTitle" class="font-display text-2xl font-700 text-[#0d1f3c] mt-4 mb-1">Add Billing</h2>
      <p class="text-sm text-gray-500 font-300">Fill in the billing details below</p>
    </div>
    <button onclick="closeBillingModal()" class="modal-close"><i class="ri-close-line"></i></button>

    <form id="billingForm" method="POST" action="save_billing.php" class="p-8 space-y-5">
      <input type="hidden" id="billing_id_hidden" name="id">

      <div class="form-group">
        <label class="form-label">Billing ID</label>
        <div class="input-wrap">
          <i class="ri-hashtag input-icon"></i>
          <input type="text" id="billing_id" name="billing_id" placeholder="e.g. BILL001" required class="form-input">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Fee Name</label>
        <div class="input-wrap">
          <i class="ri-price-tag-3-line input-icon"></i>
          <input type="text" id="fee_type" name="fee_type" placeholder="e.g. Tuition, Miscellaneous" required class="form-input">
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div class="form-group">
          <label class="form-label">Billing Date</label>
          <div class="input-wrap">
            <i class="ri-calendar-line input-icon"></i>
            <input type="date" id="billing_date" name="billing_date" required class="form-input">
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Due Date</label>
          <div class="input-wrap">
            <i class="ri-calendar-check-line input-icon"></i>
            <input type="date" id="due_date" name="due_date" required class="form-input">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <div class="input-wrap">
          <i class="ri-checkbox-circle-line input-icon"></i>
          <select id="status" name="status" required class="form-input form-select">
            <option value="">Select Status</option>
            <option value="Paid">Paid</option>
            <option value="Unpaid">Unpaid</option>
            <option value="Pending">Pending</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Total Amount</label>
        <div class="input-wrap">
          <i class="ri-money-peso-circle-line input-icon"></i>
          <input type="number" id="total_amount" name="total_amount" step="0.01" placeholder="0.00" required class="form-input">
        </div>
      </div>

      <div class="flex gap-3 pt-2">
        <button type="button" onclick="closeBillingModal()" class="btn-modal-cancel flex-1">
          Cancel
        </button>
        <button type="submit" id="billingSubmit" class="btn-submit-primary flex-1">
          <i class="ri-save-line mr-2"></i>Save Billing
        </button>
      </div>
    </form>
  </div>
</div>

<script>const events = <?php echo json_encode($events); ?>;</script>
<script src="script.js"></script>
</body>
</html>