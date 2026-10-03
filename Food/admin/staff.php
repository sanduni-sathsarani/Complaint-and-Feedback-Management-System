<?php require_once "../includes/auth.php"; require_admin();
$db=db(); $msg=""; $edit=null;

// Handle actions
if($_SERVER["REQUEST_METHOD"]==="POST"){
    $action = $_POST["action"] ?? "create";

    if($action==="create"){
        $name=trim($_POST["name"]);$email=trim($_POST["email"]);$pass=$_POST["password"];
        if($name && $email && $pass){
            $s=$db->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'staff')");
            try{ $s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT)]); $msg="Staff account created."; }
            catch(PDOException $e){ $msg="Email may already exist."; }
        }
    }

    if($action==="update"){
        $id=(int)$_POST["user_id"];
        $name=trim($_POST["name"]);$email=trim($_POST["email"]);$pass=trim($_POST["password"] ?? "");
        if($name && $email){
            try{
                if($pass !== ""){
                    $s=$db->prepare("UPDATE users SET name=?, email=?, password=? WHERE user_id=? AND role='staff'");
                    $s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT),$id]);
                }else{
                    $s=$db->prepare("UPDATE users SET name=?, email=? WHERE user_id=? AND role='staff'");
                    $s->execute([$name,$email,$id]);
                }
                $msg="Staff account updated.";
            }catch(PDOException $e){ $msg="Email may already exist."; }
        }
    }

    if($action==="toggle_active"){
        $id=(int)$_POST["user_id"];
        $s=$db->prepare("UPDATE users SET is_active = NOT is_active WHERE user_id=? AND role='staff'");
        $s->execute([$id]);
        $msg="Staff status changed.";
    }

    if($action==="delete"){
        $id=(int)$_POST["user_id"];
        try{
            $s=$db->prepare("DELETE FROM users WHERE user_id=? AND role='staff'");
            $s->execute([$id]);
            $msg="Staff account deleted.";
        }catch(PDOException $e){
            // Likely blocked by a foreign key (staff has assigned complaints) - deactivate instead.
            $s=$db->prepare("UPDATE users SET is_active=0 WHERE user_id=? AND role='staff'");
            $s->execute([$id]);
            $msg="Staff has assigned complaints and can't be deleted, so the account was deactivated instead.";
        }
    }

    redirect("staff.php");
}

// Load a staff record into the form for editing
if(isset($_GET["edit"])){
    $s=$db->prepare("SELECT user_id,name,email FROM users WHERE user_id=? AND role='staff'");
    $s->execute([(int)$_GET["edit"]]);
    $edit=$s->fetch();
}

$staff=$db->query("SELECT user_id,name,email,is_active,created_at FROM users WHERE role='staff' ORDER BY created_at DESC")->fetchAll();
include "../includes/header.php"; ?>
<div class="dashboard container">
<a href="dashboard.php">← Dashboard</a>
<h1>Manage Staff</h1>
<div class="two-col">
    <form class="form-card" method="post">
        <h2><?= $edit ? "Edit Staff" : "Add Staff" ?></h2>
        <input type="hidden" name="action" value="<?= $edit ? "update" : "create" ?>">
        <?php if($edit): ?><input type="hidden" name="user_id" value="<?= (int)$edit["user_id"] ?>"><?php endif; ?>
        <label>Name<input name="name" value="<?= $edit ? e($edit["name"]) : "" ?>" required></label>
        <label>Email<input type="email" name="email" value="<?= $edit ? e($edit["email"]) : "" ?>" required></label>
        <label><?= $edit ? "New Password (leave blank to keep current)" : "Password" ?>
            <input type="password" name="password" minlength="6" <?= $edit ? "" : "required" ?>>
        </label>
        <button class="btn btn-primary"><?= $edit ? "Save Changes" : "Create Staff" ?></button>
        <?php if($edit): ?><a class="btn btn-small" href="staff.php" style="margin-left:8px">Cancel</a><?php endif; ?>
        <?php if($msg): ?><p class="success-text"><?= e($msg) ?></p><?php endif; ?>
    </form>
    <div class="table-card">
        <table>
            <tr><th>Name</th><th>Email</th><th>Active</th><th>Actions</th></tr>
            <?php foreach($staff as $s): ?>
            <tr>
                <td><?= e($s["name"]) ?></td>
                <td><?= e($s["email"]) ?></td>
                <td><?= $s["is_active"]?"Yes":"No" ?></td>
                <td>
                    <div class="inline-form">
                        <a class="btn btn-small" href="staff.php?edit=<?= (int)$s["user_id"] ?>">Edit</a>
                        <form method="post" style="display:inline">
                            <input type="hidden" name="action" value="toggle_active">
                            <input type="hidden" name="user_id" value="<?= (int)$s["user_id"] ?>">
                            <button class="btn btn-small"><?= $s["is_active"] ? "Deactivate" : "Activate" ?></button>
                        </form>
                        <form method="post" style="display:inline" onsubmit="return confirm('Delete this staff account?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= (int)$s["user_id"] ?>">
                            <button class="btn btn-small">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
</div>
<?php include "../includes/footer.php"; ?>
