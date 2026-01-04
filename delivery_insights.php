<?php
// Include the database connection file
include 'db_connect.php';

// --- Metrics Calculations ---
// Fetch today's sales (all orders, not just delivered)
$today = date('Y-m-d');
$todaySalesQuery = "SELECT SUM(total_amount) AS total_today FROM orders WHERE DATE(order_date) = '$today'";
$todaySalesResult = $conn->query($todaySalesQuery);
$totalSalesToday = $todaySalesResult->fetch_assoc()['total_today'] ?? 0;

// Fetch total sales to date (all orders)
$totalSalesQuery = "SELECT SUM(total_amount) AS total_sales FROM orders";
$totalSalesResult = $conn->query($totalSalesQuery);
$totalSalesToDate = $totalSalesResult->fetch_assoc()['total_sales'] ?? 0;

// Fetch total orders (all statuses)
$totalQuery = "SELECT COUNT(*) as total FROM orders";
$totalResult = $conn->query($totalQuery);
$totalOrders = $totalResult->fetch_assoc()['total'];

// Calculate Average Order Value (AOV) for all orders
$averageOrderValue = ($totalOrders > 0) ? ($totalSalesToDate / $totalOrders) : 0;

// --- Chart Data Preparation ---
// Fetch monthly sales data for the last 6 months (all orders)
$monthlySalesQuery = "
    SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, SUM(total_amount) AS total_sales
    FROM orders
    WHERE order_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY month
    ORDER BY month ASC
";
$monthlySalesResult = $conn->query($monthlySalesQuery);
$monthlySalesData = [];
while ($row = $monthlySalesResult->fetch_assoc()) {
    $monthlySalesData[] = $row;
}

// Fetch sales by category (all orders)
$salesByCategoryQuery = "
    SELECT oi.product_table AS category, SUM(oi.quantity * p.price) AS total_sales
    FROM order_items oi
    JOIN (
        SELECT id, price FROM mountain UNION ALL
        SELECT id, price FROM gravel UNION ALL
        SELECT id, price FROM road UNION ALL
        SELECT id, price FROM ch UNION ALL
        SELECT id, price FROM kids UNION ALL
        SELECT id, price FROM ebikes UNION ALL
        SELECT id, price FROM accessories UNION ALL
        SELECT id, price FROM clothing UNION ALL
        SELECT id, price FROM top_products
    ) p ON oi.product_id = p.id
    GROUP BY category
    ORDER BY total_sales DESC
";
$salesByCategoryResult = $conn->query($salesByCategoryQuery);
$salesByCategoryData = [];
while ($row = $salesByCategoryResult->fetch_assoc()) {
    $salesByCategoryData[] = $row;
}


// --- Status Counts ---
// Fetch count of each status
$statusQuery = "SELECT status, COUNT(*) as count FROM orders GROUP BY status";
$statusResult = $conn->query($statusQuery);

$statuses = [];
while ($row = $statusResult->fetch_assoc()) {
    $statuses[$row['status']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delivery & Sales Insights</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #121212; /* Dark background */
      margin: 0;
      padding: 20px;
      color: #e0e0e0; /* Light text */
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #f1f1f1;
    }

    .insight-container {
      max-width: 1200px;
      margin: 0 auto;
      background: #1e1e1e; /* Slightly lighter dark background */
      padding: 20px;
      box-shadow: 0 0 8px rgba(0,0,0,0.3);
      border-radius: 8px;
    }

    .metrics-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 30px;
    }

    .metric-card {
      background-color: #2c2c2c; /* Dark card background */
      border: 1px solid #3a3a3a;
      border-radius: 8px;
      padding: 20px;
      flex: 1;
      min-width: 200px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .metric-card h3 {
      font-size: 1rem;
      color: #b0b0b0; /* Lighter text for heading */
      margin: 0 0 10px 0;
    }

    .metric-card .value {
      font-size: 2rem;
      font-weight: 700;
      color: #87ceeb; /* A bright blue for contrast */
    }

    .metric-card.sales .value {
      color: #90ee90; /* A bright green for sales */
    }
    
    .metric-card.avg-value .value {
        color: #ffcc66; /* Orange for average value */
    }

    .chart-container {
      background-color: #2c2c2c;
      padding: 20px;
      border-radius: 8px;
      border: 1px solid #3a3a3a;
      margin-bottom: 30px;
      position: relative;
      height: 400px; /* Added fixed height */
    }
    
    .chart-title {
        color: #f1f1f1;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 12px 15px;
      border-bottom: 1px solid #3a3a3a;
      text-align: left;
    }
    
    .bar-container {
      background: #444; /* Darker background for bars */
      height: 18px;
      border-radius: 10px;
      overflow: hidden;
      margin-top: 6px;
    }

    .bar {
      height: 100%;
      text-align: right;
      color: white;
      font-size: 12px;
      padding-right: 8px;
      line-height: 18px;
    }

    .delivered { background: #2ecc71; }
    .shipped { background: #3498db; }
    .cancelled { background: #e74c3c; }
    .pending { background: #f1c40f; }
    .processing { background: #9b59b6; }
    .received { background: #607d8b; }
    
    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 0.9rem;
      color: #999;
    }

    .chart-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    
    canvas {
        display: block;
        height: 100%;
        width: 100%;
    }
    
    @media (max-width: 900px) {
        .chart-grid {
            grid-template-columns: 1fr;
        }
    }
  </style>
</head>
<body>

<div class="insight-container">
  <h2>📦 Delivery & Sales Insights</h2>

  <div class="metrics-container">
    <div class="metric-card">
      <h3>Total Orders Received</h3>
      <div class="value"><?= $totalOrders ?></div>
    </div>
    <div class="metric-card sales">
      <h3>Total Sales Today</h3>
      <div class="value">₹<?= number_format($totalSalesToday, 2) ?></div>
    </div>
    <div class="metric-card sales">
      <h3>Total Sales Till Now</h3>
      <div class="value">₹<?= number_format($totalSalesToDate, 2) ?></div>
    </div>
    <div class="metric-card avg-value">
      <h3>Average Order Value</h3>
      <div class="value">₹<?= number_format($averageOrderValue, 2) ?></div>
    </div>
  </div>

  <div class="chart-grid">
    <div class="chart-container">
        <div class="chart-title">Monthly Sales Trend (Last 6 Months)</div>
        <canvas id="monthlySalesChart"></canvas>
    </div>
    <div class="chart-container">
        <div class="chart-title">Sales by Category</div>
        <canvas id="salesByCategoryChart"></canvas>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Status</th>
        <th>Count</th>
        <th>Progress</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $statusList = ['pending', 'shipped', 'delivered', 'received', 'cancelled', 'processing'];
      foreach ($statusList as $status):
        $count = $statuses[$status] ?? 0;
        $percent = $totalOrders > 0 ? ($count / $totalOrders) * 100 : 0;
        $class = strtolower($status);
      ?>
      <tr>
        <td><?= ucwords($status) ?></td>
        <td><?= $count ?></td>
        <td>
          <div class="bar-container">
            <div class="bar <?= $class ?>" style="width: <?= $percent ?>%"><?= round($percent) ?>%</div>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="footer">
    <!-- Footer content can be placed here or removed -->
  </div>
</div>

<script>
    // PHP data is passed to JavaScript
    const monthlySalesData = <?= json_encode($monthlySalesData) ?>;
    const salesByCategoryData = <?= json_encode($salesByCategoryData) ?>;
    
    // Monthly Sales Chart
    const monthlySalesCtx = document.getElementById('monthlySalesChart').getContext('2d');
    new Chart(monthlySalesCtx, {
        type: 'line',
        data: {
            labels: monthlySalesData.map(d => d.month),
            datasets: [{
                label: 'Total Sales (₹)',
                data: monthlySalesData.map(d => d.total_sales),
                borderColor: '#90ee90',
                backgroundColor: 'rgba(144, 238, 144, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#3a3a3a' },
                    ticks: { color: '#e0e0e0' }
                },
                x: {
                    grid: { color: '#3a3a3a' },
                    ticks: { color: '#e0e0e0' }
                }
            },
            plugins: {
                legend: {
                    labels: { color: '#e0e0e0' }
                }
            }
        }
    });

    // Sales by Category Chart
    const salesByCategoryCtx = document.getElementById('salesByCategoryChart').getContext('2d');
    const backgroundColors = [
      'rgba(135, 206, 235, 0.6)', // Sky Blue
      'rgba(144, 238, 144, 0.6)', // Light Green
      'rgba(255, 204, 102, 0.6)', // Orange
      'rgba(240, 128, 128, 0.6)', // Light Coral
      'rgba(147, 112, 219, 0.6)', // Medium Purple
      'rgba(102, 205, 170, 0.6)', // Medium Aquamarine
    ];

    new Chart(salesByCategoryCtx, {
        type: 'bar',
        data: {
            labels: salesByCategoryData.map(d => d.category),
            datasets: [{
                label: 'Total Sales (₹)',
                data: salesByCategoryData.map(d => d.total_sales),
                backgroundColor: backgroundColors,
                borderColor: backgroundColors.map(c => c.replace('0.6', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#3a3a3a' },
                    ticks: { color: '#e0e0e0' }
                },
                x: {
                    grid: { color: '#3a3a3a' },
                    ticks: { color: '#e0e0e0' }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
</script>

</body>
</html>
<?php $conn->close(); ?>
