<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }

$id = intval($_GET['id'] ?? 0);
$r = null;
if($id>0){
  $stmt = $conn->prepare("SELECT * FROM repairs WHERE id=?");
  $stmt->bind_param("i",$id);
  $stmt->execute();
  $res = $stmt->get_result();
  $r = $res->fetch_assoc();
}
if(!$r){ die("Repair not found."); }

$subtotal = floatval($r['cost']);
$tax = round($subtotal * 0.075, 2); // 7.5% VAT sample
$total = $subtotal + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Invoice #<?php echo e($r['id']); ?> - SmartFix</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .invoice{background:#f9fbfd;padding:20px;border-radius:10px;margin-top:20px}
    .top{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
    .grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px}
    .table th{background:#1a4e8e;color:#fff}
    @media(max-width:700px){.top{flex-direction:column;gap:10px}.grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
<div class="container" style="padding:20px">
  <div class="invoice">
    <div class="top">
      <div style="display:flex;align-items:center;gap:10px">
        <img src="uploads/logo.png" alt="Logo" style="height:60px">
        <div>
          <h2 style="color:#1a4e8e;margin:0">S & K SMARTFIX</h2>
          <small>123 Tech Street, Phone City</small>
        </div>
      </div>
      <div style="text-align:right">
        <h3 style="margin:0">INVOICE</h3>
        <p>Date: <?php echo date('F j, Y'); ?><br>Invoice #: INV-<?php echo e($r['id']); ?></p>
      </div>
    </div>
    <div class="grid">
      <div>
        <h3 style="color:#1a4e8e">Bill To:</h3>
        <p><?php echo e($r['customer']); ?><br>
        Phone: <?php echo e($r['phone']); ?></p>
      </div>
      <div>
        <h3 style="color:#1a4e8e">Repair Details:</h3>
        <p>Job ID: #JOB-<?php echo e($r['id']); ?><br>
        Device: <?php echo e($r['device']); ?> <?php echo e($r['model']); ?><br>
        IMEI: <?php echo e($r['imei']); ?><br>
        Status: <?php echo e($r['status']); ?></p>
      </div>
    </div>
    <table class="table" style="width:100%;border-collapse:collapse">
      <thead>
        <tr><th style="padding:12px;text-align:left">Description</th><th style="padding:12px;text-align:right">Qty</th><th style="padding:12px;text-align:right">Unit Price</th><th style="padding:12px;text-align:right">Total</th></tr>
      </thead>
      <tbody>
        <tr>
          <td style="padding:12px;border-bottom:1px solid #eee">Repair Service</td>
          <td style="padding:12px;text-align:right;border-bottom:1px solid #eee">1</td>
          <td style="padding:12px;text-align:right;border-bottom:1px solid #eee">₦<?php echo number_format($subtotal,2); ?></td>
          <td style="padding:12px;text-align:right;border-bottom:1px solid #eee">₦<?php echo number_format($subtotal,2); ?></td>
        </tr>
        <tr>
          <td colspan="3" style="padding:12px;text-align:right;font-weight:bold">Subtotal</td>
          <td style="padding:12px;text-align:right">₦<?php echo number_format($subtotal,2); ?></td>
        </tr>
        <tr>
          <td colspan="3" style="padding:12px;text-align:right;font-weight:bold">Tax (7.5%)</td>
          <td style="padding:12px;text-align:right">₦<?php echo number_format($tax,2); ?></td>
        </tr>
        <tr>
          <td colspan="3" style="padding:12px;text-align:right;font-weight:bold">Total</td>
          <td style="padding:12px;text-align:right;font-weight:bold">₦<?php echo number_format($total,2); ?></td>
        </tr>
      </tbody>
    </table>
    <div style="margin-top:20px">
      <p>CEO: <b>Abubakar Adamu Adam</b></p>
      <p>Thank you for your business! Payment is due within 15 days of invoice date.</p>
    </div>
    <div style="margin-top:10px">
      <button onclick="window.print()">Print / Save as PDF</button>
      <a href="index.php"><button>Back</button></a>
    </div>
  </div>
</div>
</body>
</html>
