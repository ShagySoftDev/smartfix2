<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $customer = $_POST['customer'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $device = $_POST['device'] ?? '';
    $model = $_POST['model'] ?? '';
    $imei = $_POST['imei'] ?? '';
    $issue = $_POST['issue'] ?? '';
    $cond_text = $_POST['cond_text'] ?? '';
    $cost = floatval($_POST['cost'] ?? 0);

    $sql = "INSERT INTO repairs (customer,phone,device,model,imei,issue,cond_text,cost,status) VALUES (?,?,?,?,?,?,?,?, 'Received')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssd",$customer,$phone,$device,$model,$imei,$issue,$cond_text,$cost);
    $stmt->execute();
}
header("Location: index.php");
exit;
?>
