<?php
require_once './includes/header.php';
LogInCheck();
require_once './includes/admin_nav.php';
flash();
require_once 'db.php';

// --- Fetch data once and store in variables ---
$sql = "SELECT COUNT(`dept_id`) AS dept_available FROM `department` WHERE `dept_id` <> '1'";
$result0 = $conn->query($sql);
$row0 = $result0->fetch_assoc();
$deptCount = $row0['dept_available'];

$sql = "SELECT COUNT(`supplier_id`) AS supplier_available FROM `supplier`";
$result1 = $conn->query($sql);
$row1 = $result1->fetch_assoc();
$supplierCount = $row1['supplier_available'];

$sql = "SELECT COUNT(`item_id`) AS item_available FROM `item` WHERE `dept_id` = 1";
$result2 = $conn->query($sql);
$row2 = $result2->fetch_assoc();
$itemCount = $row2['item_available'];
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .stat-card {
        background: #ffffffcc;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        transition: 0.3s ease-in-out;
    }

    .stat-card:hover {
        transform: scale(1.02);
    }

    .dashboard-title {
        text-align: center;
        margin-top: 30px;
        font-weight: bold;
        font-size: 28px;
    }

    .chart-container {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .chart-container canvas {
        width: 100% !important;
        height: 300px !important;
    }
</style>

<div class="container">
    <h2 class="dashboard-title">Admin Dashboard Overview</h2>
    <div class="row">

        <?php if ($_SESSION['role'] == 'admin') { ?>
            <!-- Department -->
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <h4>Total Departments</h4>
                    <h2 class='text-primary'><?= $deptCount ?></h2>
                </div>
            </div>

            <!-- Suppliers -->
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <h4>Available Suppliers</h4>
                    <h2 class='text-success'><?= $supplierCount ?></h2>
                </div>
            </div>

            <!-- Items -->
            <div class="col-md-4">
                <div class="stat-card text-center">
                    <h4>Items In Stock</h4>
                    <h2 class='text-danger'><?= $itemCount ?></h2>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Bar Chart -->
        <div class="col-md-6">
            <div class="chart-container">
                <h4 class="text-center">Bar Chart - Resource Counts</h4>
                <canvas id="adminChart"></canvas>
            </div>
        </div>

        <!-- Pie Chart -->
        <div class="col-md-6">
            <div class="chart-container">
                <h4 class="text-center">Pie Chart - Resource Distribution</h4>
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    // Bar Chart
    const ctx = document.getElementById('adminChart').getContext('2d');
    const adminChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Departments', 'Suppliers', 'Items'],
            datasets: [{
                label: 'Total Count',
                data: [<?= $deptCount ?>, <?= $supplierCount ?>, <?= $itemCount ?>],
                backgroundColor: ['#007bff', '#28a745', '#dc3545'],
                borderColor: ['#0056b3', '#1c7c33', '#a71d2a'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Departments', 'Suppliers', 'Items'],
            datasets: [{
                data: [<?= $deptCount ?>, <?= $supplierCount ?>, <?= $itemCount ?>],
                backgroundColor: ['#17a2b8', '#ffc107', '#6f42c1'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>

<?php
// Optional: backup confirmation
if (isset($bak) && $bak == 1) {
    echo '<script>alert("Backup taken");</script>';
}
?>

<?php require_once './includes/footer.php'; ?>
