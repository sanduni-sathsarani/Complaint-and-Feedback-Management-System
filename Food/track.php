<?php
require_once "includes/functions.php";
$number = trim($_GET["number"] ?? $_POST["complaint_number"] ?? "");
$complaint = null; $history = []; $responses = [];
if ($number !== "") {
    $s = db()->prepare("SELECT c.*, cat.category_name FROM complaints c LEFT JOIN categories cat ON c.category_id=cat.category_id WHERE c.complaint_number=?");
    $s->execute([$number]); $complaint = $s->fetch();
    if ($complaint) {
        $h = db()->prepare("SELECT status,note,created_at FROM complaint_status_history WHERE complaint_id=? ORDER BY created_at");
        $h->execute([$complaint["complaint_id"]]); $history = $h->fetchAll();
        $r = db()->prepare("SELECT message,created_at FROM complaint_responses WHERE complaint_id=? ORDER BY created_at DESC");
        $r->execute([$complaint["complaint_id"]]); $responses = $r->fetchAll();
    }
}
include "includes/header.php";
?>
<div class="container page">
    <div class="form-heading"><span class="badge">TRACK COMPLAINT</span><h1>Check your complaint status</h1></div>
    <form class="track-form" method="post"><input name="complaint_number" value="<?= e($number) ?>" placeholder="Enter complaint number" required><button class="btn btn-primary">Track</button></form>
    <?php if ($number && !$complaint): ?><div class="alert error">No complaint was found with that number.</div><?php endif; ?>
    <?php if ($complaint): ?>
        <div class="track-card">
            <div class="track-top"><div><small>Complaint Number</small><h2><?= e($complaint["complaint_number"]) ?></h2></div><span class="status"><?= e($complaint["status"]) ?></span></div>
            <div class="detail-grid">
                <div><small>Subject</small><strong><?= e($complaint["subject"]) ?></strong></div>
                <div><small>Category</small><strong><?= e($complaint["category_name"]) ?></strong></div>
                <div><small>Branch</small><strong><?= e($complaint["branch"] ?: "Not specified") ?></strong></div>
                <div><small>Submitted</small><strong><?= e($complaint["created_at"]) ?></strong></div>
            </div>
            <h3>Status Timeline</h3>
            <div class="timeline">
            <?php foreach ($history as $h): ?><div class="timeline-item"><b><?= e($h["status"]) ?></b><span><?= e($h["created_at"]) ?></span><p><?= e($h["note"]) ?></p></div><?php endforeach; ?>
            </div>
            <?php if ($responses): ?><h3>Restaurant Response</h3><?php foreach ($responses as $r): ?><div class="response-box"><p><?= nl2br(e($r["message"])) ?></p><small><?= e($r["created_at"]) ?></small></div><?php endforeach; ?><?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<?php include "includes/footer.php"; ?>