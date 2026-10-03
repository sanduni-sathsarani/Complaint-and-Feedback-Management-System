<?php require_once "../includes/auth.php"; require_admin();
$db=db(); $number=trim($_GET["number"]??"");
$s=$db->prepare("SELECT c.*,cat.category_name FROM complaints c LEFT JOIN categories cat ON c.category_id=cat.category_id WHERE c.complaint_number=?"); $s->execute([$number]); $c=$s->fetch(); if(!$c) exit("Complaint not found");
if($_SERVER["REQUEST_METHOD"]==="POST"){
    if(isset($_POST["status"])) set_status($c["complaint_id"],$_POST["status"],trim($_POST["note"]??"Status updated by administrator."),$_SESSION["user_id"]);
    if(trim($_POST["message"]??"")!==""){ $db->prepare("INSERT INTO complaint_responses(complaint_id,user_id,message) VALUES(?,?,?)")->execute([$c["complaint_id"],$_SESSION["user_id"],trim($_POST["message"])]); if($c["status"]!=="Resolved") set_status($c["complaint_id"],"Response Sent","Restaurant response sent to customer.",$_SESSION["user_id"]); }
    redirect("complaint_view.php?number=".urlencode($number));
}
$h=$db->prepare("SELECT h.*,u.name FROM complaint_status_history h LEFT JOIN users u ON h.changed_by=u.user_id WHERE complaint_id=? ORDER BY h.created_at DESC");$h->execute([$c["complaint_id"]]);$history=$h->fetchAll();
include "../includes/header.php"; ?>
<div class="dashboard container"><a href="complaints.php">← Complaints</a><div class="detail-header"><div><span class="badge"><?= e($c["complaint_number"]) ?></span><h1><?= e($c["subject"]) ?></h1></div><span class="status"><?= e($c["status"]) ?></span></div>
<div class="detail-grid big"><div><small>Customer</small><strong><?= e($c["customer_name"]) ?></strong></div><div><small>Email</small><strong><?= e($c["customer_email"] ?: "—") ?></strong></div><div><small>Phone</small><strong><?= e($c["customer_phone"] ?: "—") ?></strong></div><div><small>Category</small><strong><?= e($c["category_name"]) ?></strong></div><div class="full"><small>Description</small><p><?= nl2br(e($c["description"])) ?></p></div></div>
<div class="two-col"><form class="form-card" method="post"><h2>Update Complaint</h2><label>Status<select name="status"><?php foreach(["Submitted","Under Review","Assigned","Investigating","Response Sent","Resolved","Closed","Reopened"] as $st): ?><option <?= $c["status"]===$st?"selected":"" ?>><?= $st ?></option><?php endforeach; ?></select></label><label>Note<textarea name="note" rows="4"></textarea></label><button class="btn btn-primary">Update Status</button></form>
<form class="form-card" method="post"><h2>Respond to Customer</h2><label>Message<textarea name="message" rows="8" required></textarea></label><button class="btn btn-primary">Send Response</button></form></div>
<div class="table-card"><h2>Status History</h2><table><tr><th>Status</th><th>Note</th><th>Changed By</th><th>Date</th></tr><?php foreach($history as $x): ?><tr><td><?= e($x["status"]) ?></td><td><?= e($x["note"]) ?></td><td><?= e($x["name"]?: "Customer") ?></td><td><?= e($x["created_at"]) ?></td></tr><?php endforeach; ?></table></div></div>
<?php include "../includes/footer.php"; ?>