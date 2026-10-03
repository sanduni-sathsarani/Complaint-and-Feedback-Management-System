<?php require_once "../includes/auth.php"; require_admin();
$db=db();$msg="";
if($_SERVER["REQUEST_METHOD"]==="POST" && trim($_POST["category_name"]??"")!==""){ $s=$db->prepare("INSERT INTO categories(category_name,description) VALUES(?,?)");$s->execute([trim($_POST["category_name"]),trim($_POST["description"]??"")]);$msg="Category added."; }
$cats=$db->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
include "../includes/header.php"; ?>
<div class="dashboard container">
    <a href="dashboard.php">← Dashboard</a>
    <h1>Complaint Categories</h1><div class="two-col">
        <form class="form-card" method="post">
            <label>Category Name<input name="category_name" required></label>
            <label>Description<textarea name="description"></textarea></label>
            <button class="btn btn-primary">Add Category</button><?php if($msg): ?>
                <p class="success-text"><?= e($msg) ?></p><?php endif; ?>
            </form><div class="table-card"><table><tr><th>Category</th><th>Description</th></tr>
            <?php foreach($cats as $c): ?>
                <tr>
                    <td><?= e($c["category_name"]) ?></td>
                    <td><?= e($c["description"]) ?></td>
                </tr><?php endforeach; ?>
            </table></div></div></div>
<?php include "../includes/footer.php"; ?>