<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST'){
    $part_name = $_POST['part_name'] ?? '';
    $compatible_with = $_POST['compatible_with'] ?? '';
    $stock = intval($_POST['stock'] ?? 0);
    $price = floatval($_POST['price'] ?? 0);

    $stmt = $conn->prepare("INSERT INTO inventory (part_name,compatible_with,stock,price) VALUES (?,?,?,?)");
    $stmt->bind_param("ssid",$part_name,$compatible_with,$stock,$price);
    $stmt->execute();
}
header("Location: index.php#inventory");
exit;
?>
