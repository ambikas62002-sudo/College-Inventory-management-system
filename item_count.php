<?php
require_once './includes/header.php';
LogInCheck();
require_once './includes/admin_nav.php';
require_once 'db.php';
?>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Bootstrap Modal support -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<div class="container mt-4">
    <h3 class="text-center">Item Distribution Overview</h3>

    <div class="row mt-4">
        <!-- Department-Wise -->
        <div class="col-md-6">
            <h4>Department-wise Item Count</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Total Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $deptData = [];
                    $deptSql = "SELECT d.dept_id, d.dept_name, COUNT(i.item_id) AS item_count
                                FROM department d
                                LEFT JOIN item i ON d.dept_id = i.dept_id
                                GROUP BY d.dept_id
                                ORDER BY item_count DESC";
                    $deptResult = $conn->query($deptSql);
                    while ($row = $deptResult->fetch_assoc()) {
                        $deptData[] = $row;
                        echo "<tr>
                                <td>{$row['dept_name']}</td>
                                <td><a href='#deptModal{$row['dept_id']}' data-toggle='modal'>{$row['item_count']}</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Supplier-Wise -->
        <div class="col-md-6">
            <h4>Supplier-wise Item Count</h4>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Supplier</th>
                        <th>Total Items</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $supplierData = [];
                    $supplierSql = "SELECT s.supplier_id, s.supplier_name, COUNT(i.item_id) AS item_count
                                    FROM supplier s
                                    LEFT JOIN item i ON s.supplier_id = i.supplier_id
                                    GROUP BY s.supplier_id
                                    ORDER BY item_count DESC";
                    $supplierResult = $conn->query($supplierSql);
                    while ($row = $supplierResult->fetch_assoc()) {
                        $supplierData[] = $row;
                        echo "<tr>
                                <td>{$row['supplier_name']}</td>
                                <td><a href='#supplierModal{$row['supplier_id']}' data-toggle='modal'>{$row['item_count']}</a></td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mt-4">
        <div class="col-md-6">
            <h5 class="text-center">Department-wise Bar Chart</h5>
            <canvas id="deptChart"></canvas>
        </div>
        <div class="col-md-6">
            <h5 class="text-center">Supplier-wise Bar Chart</h5>
            <canvas id="supplierChart"></canvas>
        </div>
    </div>
</div>

<!-- Department Modals -->
<?php foreach ($deptData as $dept): ?>
<div class="modal fade" id="deptModal<?= $dept['dept_id'] ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Items in <?= $dept['dept_name'] ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php
        $deptId = $dept['dept_id'];
        $itemsSql = "SELECT item_name, item_cat, item_detail FROM item WHERE dept_id = $deptId";
        $items = $conn->query($itemsSql);
        if ($items->num_rows > 0) {
            echo "<table class='table table-bordered'><tr><th>Item</th><th>Category</th><th>Detail</th></tr>";
            while ($item = $items->fetch_assoc()) {
                echo "<tr>
                        <td>{$item['item_name']}</td>
                        <td>{$item['item_cat']}</td>
                        <td>{$item['item_detail']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No items found for this department.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Supplier Modals -->
<?php foreach ($supplierData as $sup): ?>
<div class="modal fade" id="supplierModal<?= $sup['supplier_id'] ?>" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Items from <?= $sup['supplier_name'] ?></h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <?php
        $supId = $sup['supplier_id'];
        $itemsSql = "SELECT item_name, item_cat, item_detail FROM item WHERE supplier_id = $supId";
        $items = $conn->query($itemsSql);
        if ($items->num_rows > 0) {
            echo "<table class='table table-bordered'><tr><th>Item</th><th>Category</th><th>Detail</th></tr>";
            while ($item = $items->fetch_assoc()) {
                echo "<tr>
                        <td>{$item['item_name']}</td>
                        <td>{$item['item_cat']}</td>
                        <td>{$item['item_detail']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No items found for this supplier.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<!-- Chart Script -->
<script>
    const deptChart = new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($deptData, 'dept_name')) ?>,
            datasets: [{
                label: 'Total Items',
                data: <?= json_encode(array_column($deptData, 'item_count')) ?>,
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const supplierChart = new Chart(document.getElementById('supplierChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($supplierData, 'supplier_name')) ?>,
            datasets: [{
                label: 'Total Items',
                data: <?= json_encode(array_column($supplierData, 'item_count')) ?>,
                backgroundColor: '#28a745'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

<?php require_once './includes/footer.php'; ?>
