<?php
require '../dbconfig.php';

$message = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date_time = $_POST['date'];
    $item = $_POST['item'];
    $quantity = (int) $_POST['qty'];
    $price = (float) $_POST['price'];
    $payment = $_POST['payment_method'];
    $total = $quantity * $price;

    $stmt = $conn->prepare("INSERT INTO sales (date, item, qty, price, total, payment_method) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$date_time, $item, $quantity, $price, $total, $payment])) {
        $message = "Sale recorded successfully!";
    } else {
        $message = "Error recording sale.";
    }
}

// Get today's sales
$today = date('Y-m-d');
$salesTodayStmt = $conn->prepare("SELECT * FROM sales WHERE DATE(date) = ?");
$salesTodayStmt->execute([$today]);
$salesToday = $salesTodayStmt->fetchAll();

// Calculate totals
$totalQty = 0;
$totalAmount = 0.0;
foreach ($salesToday as $sale) {
    $totalQty += $sale['qty'];
    $totalAmount += $sale['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Entry - OTC Accounting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Sales Entry</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <form method="post" class="card p-4 shadow-sm bg-white">
        <div class="mb-3">
            <label for="date" class="form-label">Date & Time</label>
            <input type="datetime-local" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="item" class="form-label">Item</label>
            <input type="text" class="form-control" id="item" name="item" required>
        </div>
        <div class="mb-3">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="qty" name="qty" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price per Unit (GHS)</label>
            <input type="number" class="form-control" step="0.01" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" id="payment_method" required>
                <option value="Cash">Cash</option>
                <option value="Mobile Money">Mobile Money</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Record Sale</button>
    </form>

    <div class="mt-4">
        <a href="../index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <h4 class="mt-5">Today's Sales (<?= $today ?>)</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
        <tr>
            <th>Time</th>
            <th>Item</th>
            <th>Qty</th>
            <th>Price (GHS)</th>
            <th>Total (GHS)</th>
            <th>Payment</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($salesToday): ?>
            <?php foreach ($salesToday as $sale): ?>
                <tr>
                    <td><?= date('H:i', strtotime($sale['date'])) ?></td>
                    <td><?= htmlspecialchars($sale['item']) ?></td>
                    <td><?= $sale['qty'] ?></td>
                    <td><?= number_format($sale['price'], 2) ?></td>
                    <td><?= number_format($sale['total'], 2) ?></td>
                    <td><?= htmlspecialchars($sale['payment_method']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">No sales recorded today</td>
            </tr>
        <?php endif; ?>
        </tbody>
        <?php if ($salesToday): ?>
        <tfoot class="table-secondary fw-bold">
            <tr>
                <td colspan="2" class="text-end">Total</td>
                <td><?= $totalQty ?></td>
                <td></td>
                <td><?= number_format($totalAmount, 2) ?></td>
                <td></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>
    
    <?php
// Initialize payment breakdown
$paymentBreakdown = [
    'Cash' => 0,
    'Mobile Money' => 0,
];

foreach ($salesToday as $sale) {
    $method = $sale['payment_method'];
    if (isset($paymentBreakdown[$method])) {
        $paymentBreakdown[$method] += $sale['total'];
    }
}
?>

<h5 class="mt-4">Breakdown by Payment Method</h5>
<ul class="list-group w-50">
    <?php foreach ($paymentBreakdown as $method => $amount): ?>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= $method ?>:
            <span><strong><?= number_format($amount, 2) ?> GHS</strong></span>
        </li>
    <?php endforeach; ?>
</ul>

</div>
</body>
</html>
