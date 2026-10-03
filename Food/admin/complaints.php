<?php require_once "../includes/auth.php"; require_admin();
$db=db();
if ($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["assign"])) {
    $cid=(int)$_POST["complaint_id"]; $sid=(int)$_POST["staff_id"];
    $db->prepare("INSERT INTO complaint_assignments(complaint_id,staff_id) VALUES(?,?)")->execute([$cid,$sid]);
    set_status($cid,"Assigned","Complaint assigned to staff member.",$_SESSION["user_id"]);
}
$complaints=$db->query("SELECT c.*,cat.category_name,(SELECT u.name FROM complaint_assignments ca JOIN users u ON ca.staff_id=u.user_id WHERE ca.complaint_id=c.complaint_id ORDER BY ca.assignment_id DESC LIMIT 1) staff_name FROM complaints c LEFT JOIN categories cat ON c.category_id=cat.category_id ORDER BY c.created_at DESC")->fetchAll();
$staff=$db->query("SELECT user_id,name FROM users WHERE role='staff' AND is_active=1 ORDER BY name")->fetchAll();
include "../includes/header.php"; ?>
<div class="dashboard container"><div class="dash-head"><div><span class="badge">ADMIN</span><h1>Complaints</h1></div><a href="dashboard.php">← Dashboard</a></div>
<div class="table-card"><table><tr><th>Number</th><th>Customer</th><th>Subject</th><th>Status</th><th>Assigned</th><th>Action</th></tr>
<?php foreach($complaints as $c): ?><tr><td><a href="complaint_view.php?number=<?= urlencode($c["complaint_number"]) ?>"><?= e($c["complaint_number"]) ?></a></td><td><?= e($c["customer_name"]) ?></td><td><?= e($c["subject"]) ?></td><td><?= e($c["status"]) ?></td><td><?= e($c["staff_name"] ?: "Not assigned") ?></td><td><form class="inline-form" method="post"><input type="hidden" name="complaint_id" value="<?= $c["complaint_id"] ?>"><select name="staff_id" required><option value="">Assign staff</option><?php foreach($staff as $s): ?><option value="<?= $s["user_id"] ?>"><?= e($s["name"]) ?></option><?php endforeach; ?></select><button name="assign" class="btn btn-small">Assign</button></form></td></tr><?php endforeach; ?></table></div></div>
<?php include "../includes/footer.php"; ?>