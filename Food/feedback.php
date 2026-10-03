<?php
require_once "includes/functions.php";
$success = false; $errors = [];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["customer_name"] ?? "");
    $email = trim($_POST["customer_email"] ?? "");
    $number = trim($_POST["complaint_number"] ?? "");
    $overall = (int)($_POST["rating"] ?? 0);
    $food = (int)($_POST["food_rating"] ?? 0);
    $service = (int)($_POST["service_rating"] ?? 0);
    $clean = (int)($_POST["cleanliness_rating"] ?? 0);
    $value = (int)($_POST["value_rating"] ?? 0);
    $comment = trim($_POST["comment"] ?? "");

    if ($overall < 1 || $overall > 5) $errors[] = "Please select an overall rating.";
    if ($comment === "") $errors[] = "Please enter a comment.";

    $complaintId = null;
    if ($number !== "") {
        $s = db()->prepare("SELECT complaint_id FROM complaints WHERE complaint_number=?");
        $s->execute([$number]);
        $complaintId = $s->fetchColumn();
        if (!$complaintId) $errors[] = "Complaint number was not found.";
    }

    if (!$errors) {
        $s = db()->prepare("INSERT INTO feedback (complaint_id, customer_name, customer_email, rating, food_rating, service_rating, cleanliness_rating, value_rating, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $s->execute([$complaintId ?: null, $name, $email, $overall, $food ?: null, $service ?: null, $clean ?: null, $value ?: null, $comment]);
        $success = true;
    }
}
include "includes/header.php";
?>
<div class="container page">
    <div class="form-heading"><span class="badge">CUSTOMER FEEDBACK</span><h1>How was your experience?</h1><p>Your feedback helps FoodCare Restaurant improve.</p></div>
    <?php if ($success): ?><div class="alert success">Thank you! Your feedback has been submitted.</div><?php endif; ?>
    <?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>
    <form class="form-card" method="post">
        <div class="form-grid">
            <label>Name<input type="text" name="customer_name"></label>
            <label>Email<input type="email" name="customer_email"></label>
            <label>Complaint Number (optional)<input type="text" name="complaint_number" placeholder="CMP-2026-0001"></label>
            <label>Overall Rating *<select name="rating" required><option value="">Select</option><?php for($i=1;$i<=5;$i++): ?><option value="<?= $i ?>"><?= $i ?> / 5</option><?php endfor; ?></select></label>
            <label>Food Quality<select name="food_rating"><option value="">Select</option><?php for($i=1;$i<=5;$i++): ?><option><?= $i ?></option><?php endfor; ?></select></label>
            <label>Customer Service<select name="service_rating"><option value="">Select</option><?php for($i=1;$i<=5;$i++): ?><option><?= $i ?></option><?php endfor; ?></select></label>
            <label>Cleanliness<select name="cleanliness_rating"><option value="">Select</option><?php for($i=1;$i<=5;$i++): ?><option><?= $i ?></option><?php endfor; ?></select></label>
            <label>Value for Money<select name="value_rating"><option value="">Select</option><?php for($i=1;$i<=5;$i++): ?><option><?= $i ?></option><?php endfor; ?></select></label>
            <label class="full">Comment *<textarea name="comment" rows="6" required></textarea></label>
        </div>
        <button class="btn btn-primary" type="submit">Send Feedback</button>
    </form>
</div>
<?php include "includes/footer.php"; ?>