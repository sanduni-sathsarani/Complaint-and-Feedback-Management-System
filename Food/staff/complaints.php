<?php require_once "../includes/auth.php";
// Staff can see ALL complaints, not just ones assigned to them.
$rows = db()->query("SELECT c.*, cat.category_name FROM complaints c LEFT JOIN categories cat ON c.category_id=cat.category_id ORDER BY c.created_at DESC")->fetchAll();
include "../includes/header.php"; ?>
<div class="dashboard container">
<a href="dashboard.php">← Dashboard</a>
<h1>All Complaints</h1>
<div class="table-card">
    <table>
        <tr><th>Number</th><th>Customer</th><th>Subject</th><th>Category</th><th>Priority</th><th>Status</th></tr>
        <?php foreach($rows as $c): ?>
        <tr>
            <td><a href="complaint_view.php?number=<?= urlencode($c["complaint_number"]) ?>"><?= e($c["complaint_number"]) ?></a></td>
            <td><?= e($c["customer_name"]) ?></td>
            <td><?= e($c["subject"]) ?></td>
            <td><?= e($c["category_name"] ?: "—") ?></td>
            <td><?= e($c["priority"]) ?></td>
            <td><span class="status"><?= e($c["status"]) ?></span></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
</div>
<?php include "../includes/footer.php"; ?>
