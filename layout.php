<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
 
$user = $_SESSION['user'] ?? null;
 
// Detect active page for sidebar highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
function isActive(string $page, string $current): string {
    return $page === $current ? 'sidebar-link active' : 'sidebar-link';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?> · CFSI</title>
 
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link rel="stylesheet" href="style.css">
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: {
          display: ["Playfair Display", "serif"],
          sans:    ["DM Sans", "sans-serif"],
        },
      },
    },
  };
</script>
<?php if (!empty($extraHead)) echo $extraHead; ?>
</head>
 
<body class="dash-body font-sans">
 
<!-- ===================== SIDEBAR ===================== -->
<aside class="sidebar" id="sidebar">
 
  <!-- Brand -->
  <div class="sidebar-brand">
    <img
      src="https://storage.readdy-site.link/project_files/e527a0c9-ac9b-4655-b294-1c9f04bf2da0/8ce5cf64-2063-4c46-8d4c-1d89754a7d16_cfsi-logo.png?v=b0327958f3048bf909fbeb15d1cbef6a"
      alt="CFSI Logo"
      class="h-9 w-auto flex-shrink-0"
    />
    <div class="sidebar-brand-text">
      <span class="sidebar-brand-title">CFSI</span>
      <span class="sidebar-brand-sub">Billing System</span>
    </div>
  </div>
 
  <!-- Navigation -->
  <nav class="sidebar-nav">
 
    <span class="sidebar-section-label">Main Menu</span>
 
    <a href="dashboard.php" class="<?php echo isActive('dashboard.php', $currentPage); ?>">
      <i class="ri-dashboard-3-line"></i><span>Dashboard</span>
    </a>
    <a href="students.php" class="<?php echo isActive('students.php', $currentPage); ?>">
      <i class="ri-user-3-line"></i><span>Students</span>
    </a>
    <a href="billing.php" class="<?php echo isActive('billing.php', $currentPage); ?>">
      <i class="ri-bill-line"></i><span>Billing</span>
    </a>
    <a href="payments.php" class="<?php echo isActive('payments.php', $currentPage); ?>">
      <i class="ri-secure-payment-line"></i><span>Payments</span>
    </a>
    <a href="reports.php" class="<?php echo isActive('reports.php', $currentPage); ?>">
      <i class="ri-bar-chart-2-line"></i><span>Reports</span>
    </a>
 
    <span class="sidebar-section-label mt-4">System</span>
 
    <a href="recycle_bin.php" class="<?php echo isActive('recycle_bin.php', $currentPage); ?>">
      <i class="ri-delete-bin-6-line"></i><span>Recycle Bin</span>
    </a>
    <a href="logs.php" class="<?php echo isActive('logs.php', $currentPage); ?>">
      <i class="ri-file-list-3-line"></i><span>Audit Logs</span>
    </a>
 
    <?php if (!empty($user) && $user['role'] === 'administrator'): ?>
    <a href="users.php" class="<?php echo isActive('users.php', $currentPage); ?>">
      <i class="ri-shield-user-line"></i><span>User Management</span>
    </a>
    <?php endif; ?>
 
  </nav>
 
  <!-- Sidebar Footer: User + Logout -->
  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-avatar">
        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
      </div>
      <div class="sidebar-user-info">
        <span class="sidebar-user-name"><?php echo htmlspecialchars($user['name']); ?></span>
        <span class="sidebar-user-role"><?php echo ucfirst($user['role']); ?></span>
      </div>
    </div>
    <a href="logout.php" class="sidebar-logout">
      <i class="ri-logout-box-r-line"></i>
      <span>Logout</span>
    </a>
  </div>
 
</aside>
 
<!-- ===================== MAIN AREA ===================== -->
<main class="dash-main">
 
  <!-- ── TOPBAR ── -->
  <header class="dash-topbar">
    <div>
      <h1 class="dash-page-title">
        <?php echo htmlspecialchars($pageTitle ?? 'Dashboard'); ?>
      </h1>
      <p class="dash-page-sub">Children of Fatima School of Sto. Tomas, Inc.</p>
    </div>
 
    <div class="topbar-right">
 
      <!-- Global Search -->
      <form action="search.php" method="GET" class="topbar-search">
        <i class="ri-search-line search-icon"></i>
        <input
          type="text"
          name="search"
          placeholder="Search system…"
          class="search-input"
        >
      </form>
 
      <!-- Notification Bell -->
      <button class="topbar-icon-btn" title="Notifications">
        <i class="ri-notification-3-line"></i>
        <span class="notif-dot"></span>
      </button>
 
      <!-- Profile Pill -->
      <div class="topbar-profile">
        <div class="topbar-avatar">
          <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
        </div>
        <span class="topbar-name hidden md:block">
          <?php echo htmlspecialchars($user['name']); ?>
        </span>
        <i class="ri-arrow-down-s-line text-gray-400 text-sm hidden md:block"></i>
      </div>
 
    </div>
  </header>
 
  <!-- ── PAGE CONTENT ── -->
  <div class="dash-content">
    <?php echo $content ?? ''; ?>
  </div>
 
</main>
 
<script src="script.js"></script>
<?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>