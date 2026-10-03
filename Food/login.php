<?php
require_once "includes/functions.php";
if (is_logged_in()) redirect("admin/dashboard.php");
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $s = db()->prepare("SELECT * FROM users WHERE email=? AND is_active=1 LIMIT 1");
    $s->execute([$email]); $user = $s->fetch();
    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["user_name"] = $user["name"];
        $_SESSION["role"] = $user["role"];
        redirect($user["role"] === "admin" ? "admin/dashboard.php" : "staff/dashboard.php");
    }
    $error = "Invalid email or password.";
}
include "includes/header.php";
?>
<div class="container page narrow">
    <div class="form-heading"><span class="badge">STAFF / ADMIN</span><h1>Sign in</h1><p>Customer accounts are not required.</p></div>
    <?php if ($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
    <form class="form-card" method="post">
        <label>Email<input type="email" name="email" required></label>
        <label>Password<input type="password" name="password" required></label>
        <button class="btn btn-primary" type="submit">Login</button>
    </form>
</div>
<?php include "includes/footer.php"; ?>