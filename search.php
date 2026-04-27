<?php
include "db.php";

$search = $_GET['search'] ?? '';
?>

<h2>Search Results for "<?php echo htmlspecialchars($search); ?>"</h2>

<!-- STUDENTS RESULT -->
<h3>Students</h3>
<?php
$stmt = $conn->prepare("SELECT * FROM students WHERE full_name LIKE ?");
$like = "%$search%";
$stmt->bind_param("s", $like);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<p>{$row['full_name']} - {$row['student_id']}</p>";
}
?>

<!-- BILLING RESULT (future) -->
<h3>Billing</h3>
<p>No billing search yet</p>