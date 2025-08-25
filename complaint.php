<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }
if($_SESSION['role']!=='engineer'){ die("Unauthorized"); }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $complaint = $_POST['complaint'] ?? '';
    $engineer = $_SESSION['username'];

    $stmt = $conn->prepare("INSERT INTO complaints (engineer,complaint) VALUES (?,?)");
    $stmt->bind_param("ss",$engineer,$complaint);
    $stmt->execute();
}
header("Location: index.php");
exit;
?>
