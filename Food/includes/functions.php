<?php
session_start();
require_once __DIR__ . "/../config/database.php";

function e($value) { return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8"); }
function redirect($path) { header("Location: $path"); exit; }
function is_logged_in() { return !empty($_SESSION["user_id"]); }
function require_login() { if (!is_logged_in()) redirect("../login.php"); }
function require_admin() { require_login(); if ($_SESSION["role"] !== "admin") { http_response_code(403); exit("Access denied"); } }
function require_staff_or_admin() { require_login(); if (!in_array($_SESSION["role"], ["admin","staff"], true)) { http_response_code(403); exit("Access denied"); } }
function generate_complaint_number() {
    $year = date("Y");
    $last = db()->query("SELECT complaint_id FROM complaints ORDER BY complaint_id DESC LIMIT 1")->fetchColumn();
    return "CMP-" . $year . "-" . str_pad((int)$last + 1, 4, "0", STR_PAD_LEFT);
}
function set_status($complaintId, $status, $note, $userId = null) {
    $s = db()->prepare("UPDATE complaints SET status=?, updated_at=NOW() WHERE complaint_id=?");
    $s->execute([$status, $complaintId]);
    $h = db()->prepare("INSERT INTO complaint_status_history (complaint_id,status,note,changed_by) VALUES (?,?,?,?)");
    $h->execute([$complaintId,$status,$note,$userId]);
}
?>