<?php
require_once "includes/functions.php";
$errors = [];
$success = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["customer_name"] ?? "");
    $email = trim($_POST["customer_email"] ?? "");
    $phone = trim($_POST["customer_phone"] ?? "");
    $order = trim($_POST["order_number"] ?? "");
    $category = (int)($_POST["category_id"] ?? 0);
    $subject = trim($_POST["subject"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $branch = trim($_POST["branch"] ?? "");
    $priority = $_POST["priority"] ?? "Medium";

    if ($name === "") $errors[] = "Name is required.";
    if ($category <= 0) $errors[] = "Please select a complaint category.";
    if ($subject === "") $errors[] = "Subject is required.";
    if ($description === "") $errors[] = "Complaint description is required.";

    if (!$errors) {
        $db = db();
        $number = generate_complaint_number();
        $stmt = $db->prepare("INSERT INTO complaints
            (complaint_number, customer_name, customer_email, customer_phone, order_number, category_id, subject, description, branch, priority, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Submitted')");
        $stmt->execute([$number, $name, $email, $phone, $order, $category, $subject, $description, $branch, $priority]);
        $id = $db->lastInsertId();

        $history = $db->prepare("INSERT INTO complaint_status_history (complaint_id, status, note) VALUES (?, 'Submitted', 'Complaint submitted by customer')");
        $history->execute([$id]);

        if (!empty($_FILES["attachment"]["name"]) && $_FILES["attachment"]["error"] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/uploads";
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
            $allowed = ["jpg","jpeg","png","pdf"];
            $ext = strtolower(pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed, true) && $_FILES["attachment"]["size"] <= 5 * 1024 * 1024) {
                $newName = uniqid("cmp_", true) . "." . $ext;
                move_uploaded_file($_FILES["attachment"]["tmp_name"], $uploadDir . "/" . $newName);
                $a = $db->prepare("INSERT INTO attachments (complaint_id, file_name, file_path) VALUES (?, ?, ?)");
                $a->execute([$id, $_FILES["attachment"]["name"], "uploads/" . $newName]);
            }
        }
        $success = $number;
    }
}
$categories = db()->query("SELECT * FROM categories WHERE is_active=1 ORDER BY category_name")->fetchAll();
include "includes/header.php";
?>
<div class="container page">
    <div class="form-heading"><span class="badge">CUSTOMER COMPLAINT</span><h1>Tell us what happened</h1><p>You do not need an account. After submission, save your complaint number.</p></div>

    <?php if ($success): ?>
        <div class="alert success"><strong>Complaint submitted successfully.</strong><br>Your complaint number is <strong><?= e($success) ?></strong>.<br><a href="track.php?number=<?= urlencode($success) ?>">Track this complaint</a></div>
    <?php endif; ?>
    <?php foreach ($errors as $error): ?><div class="alert error"><?= e($error) ?></div><?php endforeach; ?>

    <form class="form-card" method="post" enctype="multipart/form-data">
        <div class="form-grid">
            <label>Full Name *<input type="text" name="customer_name" required></label>
            <label>Email<input type="email" name="customer_email"></label>
            <label>Phone<input type="text" name="customer_phone"></label>
            <label>Order Number<input type="text" name="order_number"></label>
            <label>Category *
                <select name="category_id" required><option value="">Select category</option>
                <?php foreach ($categories as $c): ?><option value="<?= $c["category_id"] ?>"><?= e($c["category_name"]) ?></option><?php endforeach; ?>
                </select>
            </label>
            <label>Branch<input type="text" name="branch" placeholder="e.g. Badulla Branch"></label>
            <label>Priority<select name="priority"><option>Low</option><option selected>Medium</option><option>High</option></select></label>
            <label>Attachment<input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf"></label>
            <label class="full">Subject *<input type="text" name="subject" required></label>
            <label class="full">Description *<textarea name="description" rows="7" required></textarea></label>
        </div>
        <button class="btn btn-primary" type="submit">Submit Complaint</button>
    </form>
</div>
<?php include "includes/footer.php"; ?>