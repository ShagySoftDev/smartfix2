<?php
session_start();
require 'db.php';
if(!isset($_SESSION['username'])){ header("Location: login.php"); exit; }
$isCeo = ($_SESSION['role'] === 'ceo');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S & K SMARTFIX</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
  <header>
    <img src="uploads/logo.png" alt="SmartFix Logo">
    <div>
      <div class="logo" style="font-size:2rem;font-weight:700">S & K SMARTFIX</div>
      <div style="opacity:.9">Welcome, <?php echo e($_SESSION['username']); ?> (<?php echo e($_SESSION['role']); ?>)</div>
    </div>
    <div class="nav-right">
      <?php if($isCeo){ ?><a href="report.php"><button class="btn-sm"><i class="fas fa-chart-line"></i> CEO Report</button></a><?php } ?>
      <a href="logout.php"><button class="btn-sm logout"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
    </div>
  </header>

  <div class="tabs">
    <div class="tab active" onclick="switchTab('dashboard')"><i class="fas fa-chart-line"></i> Dashboard</div>
    <div class="tab" onclick="switchTab('intake')"><i class="fas fa-plus-circle"></i> New Intake</div>
    <div class="tab" onclick="switchTab('repairs')"><i class="fas fa-tools"></i> Repair Management</div>
    <div class="tab" onclick="switchTab('inventory')"><i class="fas fa-boxes"></i> Inventory</div>
    <div class="tab" onclick="switchTab('invoice')"><i class="fas fa-file-invoice-dollar"></i> Invoicing</div>
    <?php if($_SESSION['role']=='engineer'){ ?>
    <div class="tab" onclick="switchTab('complaint')"><i class="fas fa-exclamation-circle"></i> Complaint</div>
    <?php } ?>
  </div>

  <!-- DASHBOARD -->
  <div class="tab-content active" id="dashboard">
    <h2>Repair Shop Dashboard</h2>
    <div class="dashboard-stats">
      <?php
        $totalRepairs = $conn->query("SELECT COUNT(*) c FROM repairs")->fetch_assoc()['c'] ?? 0;
        $inProgress = $conn->query("SELECT COUNT(*) c FROM repairs WHERE status='In Progress'")->fetch_assoc()['c'] ?? 0;
        $revenue = $conn->query("SELECT COALESCE(SUM(cost),0) s FROM repairs")->fetch_assoc()['s'] ?? 0;
        $avgDays = 3.2; // placeholder
      ?>
      <div class="stat-card"><div class="stat-label">Total Repairs</div><div class="stat-value"><?php echo e($totalRepairs); ?></div><div class="stat-desc">All time</div></div>
      <div class="stat-card"><div class="stat-label">In Progress</div><div class="stat-value"><?php echo e($inProgress); ?></div><div class="stat-desc">Active Repairs</div></div>
      <div class="stat-card"><div class="stat-label">Revenue</div><div class="stat-value">₦<?php echo number_format($revenue,2); ?></div><div class="stat-desc">All time</div></div>
      <div class="stat-card"><div class="stat-label">Avg. Repair Time</div><div class="stat-value"><?php echo e($avgDays); ?></div><div class="stat-desc">Days (sample)</div></div>
    </div>

    <div class="card">
      <h2>Recent Repairs</h2>
      <table>
        <thead>
          <tr><th>Job ID</th><th>Customer</th><th>Device</th><th>Issue</th><th>Status</th><th>Est. Cost</th></tr>
        </thead>
        <tbody>
        <?php
          $res = $conn->query("SELECT * FROM repairs ORDER BY created_at DESC LIMIT 10");
          while($r = $res->fetch_assoc()){
            $badge = 'status-received'; if($r['status']=='In Progress') $badge='status-in-progress'; if($r['status']=='Completed') $badge='status-completed';
            echo "<tr>
                    <td>#JOB-{$r['id']}</td>
                    <td>".e($r['customer'])."</td>
                    <td>".e($r['device'])." ".e($r['model'])."</td>
                    <td>".e($r['issue'])."</td>
                    <td><span class='status-badge $badge'>".e($r['status'])."</span></td>
                    <td>₦".number_format($r['cost'],2)."</td>
                  </tr>";
          }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- INTAKE -->
  <div class="tab-content" id="intake">
    <h2>New Device Intake</h2>
    <div class="card">
      <form method="POST" action="repair_add.php">
        <div class="form-group"><label>Customer Name</label><input type="text" name="customer" required></div>
        <div class="form-group"><label>Phone Number</label><input type="tel" name="phone" required></div>
        <div class="form-group">
          <label>Device Type</label>
          <select name="device" required>
            <option value="">Select device type</option>
            <option value="iPhone">iPhone</option>
            <option value="Samsung">Samsung</option>
            <option value="Google">Google Pixel</option>
            <option value="Huawei">Huawei</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="form-group"><label>Device Model</label><input type="text" name="model" required></div>
        <div class="form-group"><label>IMEI/Serial Number</label><input type="text" name="imei"></div>
        <div class="form-group"><label>Issue Description</label><textarea name="issue" rows="3" required></textarea></div>
        <div class="form-group"><label>Device Condition</label><textarea name="cond_text" rows="2"></textarea></div>
        <div class="form-group"><label>Estimated Cost (₦)</label><input type="number" step="0.01" name="cost" required></div>
        <button type="submit"><i class="fas fa-plus-circle"></i> Add Repair Job</button>
      </form>
    </div>
  </div>

  <!-- REPAIRS -->
  <div class="tab-content" id="repairs">
    <h2>Repair Management</h2>
    <div class="card">
      <table>
        <thead><tr><th>Job ID</th><th>Customer</th><th>Device</th><th>Issue</th><th>Status</th><th>Est. Cost</th><th>Actions</th></tr></thead>
        <tbody>
          <?php
            $res = $conn->query("SELECT * FROM repairs ORDER BY id DESC");
            while($r = $res->fetch_assoc()){
              echo "<tr>
                    <td>#JOB-{$r['id']}</td>
                    <td>".e($r['customer'])."</td>
                    <td>".e($r['device'])." ".e($r['model'])."</td>
                    <td>".e($r['issue'])."</td>
                    <td>".e($r['status'])."</td>
                    <td>₦".number_format($r['cost'],2)."</td>
                    <td class='action-buttons'>
                      <a href='repair_status.php?id={$r['id']}&s=Received'><button class='btn-sm'>Received</button></a>
                      <a href='repair_status.php?id={$r['id']}&s=In%20Progress'><button class='btn-sm'>In Progress</button></a>
                      <a href='repair_status.php?id={$r['id']}&s=Completed'><button class='btn-sm btn-success'>Completed</button></a>
                      <a href='invoice.php?id={$r['id']}' target='_blank'><button class='btn-sm'><i class='fas fa-file-invoice-dollar'></i> Invoice</button></a>
                    </td>
                  </tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- INVENTORY -->
  <div class="tab-content" id="inventory">
    <h2>Inventory Management</h2>
    <div class="card">
      <form method="POST" action="inventory_add.php" class="action-buttons" style="margin-bottom:10px">
        <input class="btn-sm" type="text" name="part_name" placeholder="Part name" required>
        <input class="btn-sm" type="text" name="compatible_with" placeholder="Compatible with" required>
        <input class="btn-sm" type="number" name="stock" placeholder="Stock" required>
        <input class="btn-sm" type="number" step="0.01" name="price" placeholder="Price (₦)" required>
        <button class="btn-sm"><i class="fas fa-plus"></i> Add New Part</button>
      </form>
      <table>
        <thead><tr><th>Part ID</th><th>Part Name</th><th>Compatible With</th><th>Current Stock</th><th>Price</th><th>Actions</th></tr></thead>
        <tbody>
          <?php
            $parts = $conn->query("SELECT * FROM inventory ORDER BY id DESC");
            while($p = $parts->fetch_assoc()){
              echo "<tr>
                <td>#PART-{$p['id']}</td>
                <td>".e($p['part_name'])."</td>
                <td>".e($p['compatible_with'])."</td>
                <td>".e($p['stock'])."</td>
                <td>₦".number_format($p['price'],2)."</td>
                <td class='action-buttons'>
                  <a href='inventory_delete.php?id={$p['id']}' onclick='return confirm("Delete part?")'><button class='btn-sm btn-danger'><i class="fas fa-trash"></i></button></a>
                </td>
              </tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- INVOICE -->
  <div class="tab-content" id="invoice">
    <h2>Invoice Generation</h2>
    <div class="card">
      <div class="notice">Open <b>Repair Management</b> and click the <b>Invoice</b> button beside a completed job to preview and print.</div>
      <div class="logo-slot">
        <p><b>Logo slot:</b> Replace <code>uploads/logo.png</code> to change logo for website and receipt.</p>
      </div>
    </div>
  </div>

  <!-- COMPLAINT -->
  <?php if($_SESSION['role']=='engineer'){ ?>
  <div class="tab-content" id="complaint">
    <h2>Engineer Complaint</h2>
    <div class="card">
      <form action="complaint.php" method="POST">
        <div class="form-group">
          <label>Your Complaint</label>
          <textarea name="complaint" rows="4" placeholder="Describe your complaint" required></textarea>
        </div>
        <button type="submit">Submit Complaint</button>
      </form>
    </div>
  </div>
  <?php } ?>

</div>

<script>
function switchTab(tabName){
  document.querySelectorAll('.tab-content').forEach(t=>t.classList.remove('active'));
  document.getElementById(tabName).classList.add('active');
  document.querySelectorAll('.tab').forEach(tab=>tab.classList.remove('active'));
  const labels = {"dashboard":"Dashboard","intake":"New Intake","repairs":"Repair Management","inventory":"Inventory","invoice":"Invoicing","complaint":"Complaint"};
  document.querySelectorAll('.tab').forEach(tab=>{ if(tab.textContent.includes(labels[tabName])) tab.classList.add('active'); });
}
</script>
</body>
</html>
