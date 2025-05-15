<?php
require '../dbconfig.php';

$saleStmnt = $conn->query("SELECT SUM(price*qty) AS total_sales FROM sales");
$totalsales = $saleStmnt->fetchColumn() ?? 0;


$purchaseStmt = $conn->query("SELECT SUM(total_cost) AS total_purchases FROM purchases");
$totalPurchases = $purchaseStmt->fetchColumn() ?? 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


  <style>
    body {
      min-height: 100vh;
      display: flex;
      overflow-x: hidden;
    }

    .sidebar {
      width: 250px;
      background-color: #262161;
      color: white;
      min-height: 100vh;
    }

    .sidebar a,
    .sidebar button {
      color: white;
      display: block;
      padding: 15px;
      text-decoration: none;
      background: none;
      border: none;
      width: 100%;
      text-align: left;
    }

    .sidebar a:hover,
    .sidebar button:hover {
      background-color: #24B8EE;
    }

    .main-content {
      flex-grow: 1;
      padding: 20px;
      background-color: #f8f9fa;
    }

    .collapse a {
      padding-left: 40px;
    }
  </style>
</head>

<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4 class="text-center py-3 border-bottom">
      <img src="Maxtilliz Phar. Logo.jpg" alt="Logo here" height="50px" width="50px">
      Maxtilliz Chem
    </h4>

    <a href="#">Dashboard</a>

    <!-- Sales Dropdown -->
    <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#salesMenu">
      <i class="bi bi-cart-check me-2"></i>Sales
    </button>
    <div class="collapse" id="salesMenu">
      <a href="../Sales/add_sales.php"><i class="bi bi-plus-circle me-2"></i>Add Sale</a>
      <a href="../Sales/view_sales.php"><i class="bi bi-list-ul me-2"></i>View Sales</a>
    </div>

    <!-- Inventory Dropdown -->
    <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#inventoryMenu">
      <i class="bi bi-box-seam me-2"></i>Inventory
    </button>
    <div class="collapse" id="inventoryMenu">
      <a href="../Inventory/add_inventory.php"><i class="bi bi-plus-circle me-2"></i>Add Inventory</a>
      <a href="../Inventory/invent_list_page.php"><i class="bi bi-list-ul me-2"></i>View Inventory</a>
    </div>

    <!-- Purchase Dropdown -->
    <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#purchaseMenu">
      <i class="bi bi-truck me-2"></i>Purchase
    </button>
    <div class="collapse" id="purchaseMenu">
      <a href="../Purchase/purchase_form.php"><i class="bi bi-plus-circle me-2"></i>Add Purchase</a>
      <a href="../Purchase/purchase_history.php"><i class="bi bi-clock-history me-2"></i>Purchase History</a>
    </div>

    <!-- Reports Dropdown -->
    <button class="btn-toggle" data-bs-toggle="collapse" data-bs-target="#reportsMenu">
      <i class="bi bi-bar-chart-line me-2"></i>Reports
    </button>
    <div class="collapse" id="reportsMenu">
      <a href="../Sales/sales_report_export.php"><i class="bi bi-graph-up-arrow me-2"></i>Sales Reports</a>
      <a href="../Purchase/purchase_history_report.php"><i class="bi bi-graph-down me-2"></i>Purchase Reports</a>
    </div>

    <a class="dropdown-item text" href="../Access_control/admin_login.php" target="_blank"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <h2>Welcome, Admin</h2>
    <p> Dashboard Overview.</p>

    <!-- Example Summary Cards -->
    <div class="row my-4">
      <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-cash-coin me-2"></i>Total Sales</h5>
            <p class="card-text"><?= number_format($totalsales, 2); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-bag-check me-2"></i>Total Purchases</h5>
            <p class="card-text"><?= number_format($totalPurchases, 2); ?></p>
          </div>
        </div>
      </div>
      <!-- <div class="col-md-3">
    <div class="card text-white bg-warning mb-3">
      <div class="card-body">
        <h5 class="card-title"><i class="bi bi-graph-up me-2"></i>Total Profit</h5>
        <p class="card-text">GHS 2,000.00</p>
      </div>
    </div>
  </div> -->
      <div class="col-md-3">
        <div class="card text-white bg-danger mb-3">
          <div class="card-body">
            <h5 class="card-title"><i class="bi bi-exclamation-triangle me-2"></i>Low Stock Alerts</h5>
            <p class="card-text">5 Items</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>