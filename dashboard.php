<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $user['name']; ?> 🎉</h1>

<?php if ($user['role'] == 'administrator'): ?>
    <h2>Admin Panel</h2>
    <p>You have full access.</p>
<?php else: ?>
    <h2>Staff Panel</h2>
    <p>Limited access only.</p>
<?php endif; ?>

    <a href="logout.php">Logout</a>
</body>
</html>