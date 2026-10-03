<?php require_once "../includes/auth.php";
$db = db();
$total = $db->query("SELECT COUNT(*) FROM complaints")->fetchColumn();
$open = $db->query("SELECT COUNT(*) FROM complaints WHERE status NOT IN ('Resolved','Closed')")->fetchColumn();
$resolved = $db->query("SELECT COUNT(*) FROM complaints WHERE status='Resolved'")->fetchColumn();
include "../includes/header.php"; ?>
<div class="dashboard container">
<div class="dash-head">
    <div><span class="badge">STAFF PANEL</span><h1>Welcome, <?= e($_SESSION["user_name"]) ?></h1></div>
    <a class="btn btn-dark" href="../logout.php">Logout</a>
</div>
<div class="stats">
    <div><small>Total Complaints</small><strong><?= $total ?></strong></div>
    <div><small>Open</small><strong><?= $open ?></strong></div>
    <div><small>Resolved</small><strong><?= $resolved ?></strong></div>
</div>
<div class="admin-links"><a href="complaints.php">All Complaints</a></div>
</div>
<?php include "../includes/footer.php"; ?>
