<?php
$base = "";
if (strpos($_SERVER["PHP_SELF"], "/admin/") !== false || strpos($_SERVER["PHP_SELF"], "/staff/") !== false) $base = "../";
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>FoodCare Restaurant</title><link rel="stylesheet" href="<?= $base ?>css/style.css"></head><body>
<header class="site-header"><div class="nav container"><a class="logo" href="<?= $base ?>index.php">Food<span>Care</span></a><nav><a href="<?= $base ?>index.php">Home</a><a href="<?= $base ?>complaint.php">Complaint</a><a href="<?= $base ?>feedback.php">Feedback</a><a href="<?= $base ?>track.php">Track</a><a href="<?= $base ?>contact.php">Contact</a><?php if (is_logged_in()): ?><a class="login-link" href="<?= $base ?><?= $_SESSION["role"]==="admin"?"admin/dashboard.php":"staff/dashboard.php" ?>">Dashboard</a><?php endif; ?></nav><button class="menu-btn" onclick="toggleMenu()">☰</button></div></header>
<main>