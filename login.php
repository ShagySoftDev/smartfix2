<?php
session_start();
if(isset($_SESSION['username'])){ header("Location: index.php"); exit; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SmartFix Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-box">
    <h2 style="text-align:center;color:#1a4e8e;margin-bottom:20px">S & K SMARTFIX</h2>
    <form method="POST" action="login_process.php">
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" required>
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>
      <button type="submit" style="width:100%">Login</button>
    </form>
    <p style="margin-top:15px;font-size:14px;color:#666">CEO login: <b>ceo</b> / <b>Abubakar@00</b></p>
  </div>
</body>
</html>
