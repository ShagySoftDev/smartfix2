<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }
$id = intval($_GET['id'] ?? 0);
if($id>0){
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
}
header("Location: index.php#inventory");
exit;
?>
