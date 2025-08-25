<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }
if($_SESSION['role']!=='ceo'){ die("Unauthorized"); }

$total = $conn->query("SELECT COUNT(*) c FROM repairs")->fetch_assoc()['c'] ?? 0;
$revenue = $conn->query("SELECT COALESCE(SUM(cost),0) s FROM repairs")->fetch_assoc()['s'] ?? 0;
$comp = $conn->query("SELECT COUNT(*) c FROM repairs WHERE status='Completed'")->fetch_assoc()['c'] ?? 0;
$inprog = $conn->query("SELECT COUNT(*) c FROM repairs WHERE status='In Progress'")->fetch_assoc()['c'] ?? 0;
$received = $conn->query("SELECT COUNT(*) c FROM repairs WHERE status='Received'")->fetch_assoc()['c'] ?? 0;
$complaints = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CEO Report - SmartFix</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="padding:20px">
  <h2>CEO Report - Abubakar Adamu Adam</h2>
  <p>Total Repairs: <b><?php echo e($total); ?></b></p>
  <p>Total Revenue: <b>₦<?php echo number_format($revenue,2); ?></b></p>
  <p>Completed: <b><?php echo e($comp); ?></b> | In Progress: <b><?php echo e($inprog); ?></b> | Received: <b><?php echo e($received); ?></b></p>

  <div class="card">
    <h3>Engineer Complaints</h3>
    <table>
      <thead><tr><th>Engineer</th><th>Complaint</th><th>Date</th></tr></thead>
      <tbody>
        <?php while($row=$complaints->fetch_assoc()){ ?>
        <tr>
          <td><?php echo e($row['engineer']); ?></td>
          <td><?php echo e($row['complaint']); ?></td>
          <td><?php echo e($row['created_at']); ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <a href="index.php"><button class="btn-sm"><i class="fas fa-arrow-left"></i> Back</button></a>
</div>
</body>
</html>
