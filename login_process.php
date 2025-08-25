<?php
session_start();
require 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username=? AND password=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$username,$password);
    $stmt->execute();
    $res = $stmt->get_result();
    if($user = $res->fetch_assoc()){
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?err=1");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
?>
