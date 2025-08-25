<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$s = $_GET['s'] ?? 'Received';
$allowed = ['Received','In Progress','Completed'];
if($id>0 && in_array($s,$allowed)){
    $stmt = $conn->prepare("UPDATE repairs SET status=? WHERE id=?");
    $stmt->bind_param("si",$s,$id);
    $stmt->execute();
}
header("Location: index.php#repairs");
exit;
?>
