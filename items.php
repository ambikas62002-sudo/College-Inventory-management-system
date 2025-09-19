<?php
require_once './includes/header.php';
LogInCheck();
require_once './includes/admin_nav.php';
require_once 'db.php';
?>

<div class="container">
    <!-- Flash message area -->
    <div class="row">
        <?php flash(); ?>
    </div>

    <!-- Top buttons -->
    <div class="row mb-3">
        <?php if ($_SESSION['role'] == 'admin') { ?>
            <a href="#addnew" data-toggle="modal" class="btn btn-primary">
                <span class="glyphicon glyphicon-plus"></span> New
            </a>
        <?php } ?>
        <a href="./reports/all_items.php" target="_blank" class="btn btn-success pull-right">
            <span class="glyphicon glyphicon-print"></span> PDF
        </a>
    </div>

    <!-- Title -->
    <div class="row">
        <h3 class="text-muted text-center">ALL ITEMS</h3>
    </div>

    <!-- Item Table -->
    <div class="row">
        <div class="table-responsive">
            <table id="myTable" class="table table-hover table-bordered table-striped">
                <?php
                $item_current_dept_id = $_SESSION['dept_id'];

                // Query based on user role
                $sql = ($_SESSION['role'] == 'admin') ?
                    "SELECT * FROM item 
                     JOIN department ON item.dept_id = department.dept_id 
                     JOIN supplier ON item.supplier_id = supplier.supplier_id" :
                    "SELECT * FROM item 
                     JOIN department ON item.dept_id = department.dept_id 
                     WHERE item.dept_id = '$item_current_dept_id'";

                $query = $conn->query($sql);

                // Admin Table
                if ($_SESSION['role'] == 'admin') {
                    echo '
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ITEM</th>
                            <th>CATEGORY</th>
                            <th>DETAIL</th>
                            <th>SUPPLIER</th>
                            <th>DEPARTMENT</th>
                            <th>SUPPLIED AT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>';

                    $i = 1;
                    while ($row = $query->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$i}</td>
                            <td>{$row['item_name']}</td>
                            <td>{$row['item_cat']}</td>
                            <td>{$row['item_detail']}</td>
                            <td>{$row['supplier_name']}</td>
                            <td>{$row['dept_name']}</td>
                            <td>{$row['supplied_at']}</td>
                            <td>
                                <a href='#edit_{$row['item_id']}' class='btn btn-success btn-sm' data-toggle='modal'>
                                    <span class='glyphicon glyphicon-edit'></span> Edit
                                </a>
                                <a href='#delete_{$row['item_id']}' class='btn btn-danger btn-sm' data-toggle='modal'>
                                    <span class='glyphicon glyphicon-trash'></span> Delete
                                </a>
                            </td>
                        </tr>";
                        $i++;

                        // Include modal templates (must exist)
                        include('models/edit_delete_itemModel.php');
                    }

                    echo '</tbody>';
                    // Add Item Modal
                    require_once 'models/add_itemModel.php';

                } else {
                    // Department Table
                    echo '
                    <thead>
                        <tr>
                            <th>SL NO</th>
                            <th>ID</th>
                            <th>ITEM</th>
                            <th>CATEGORY</th>
                            <th>DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>';

                    $i = 1;
                    while ($row = $query->fetch_assoc()) {
                        echo "
                        <tr>
                            <td>{$i}</td>
                            <td>{$row['item_id']}</td>
                            <td>{$row['item_name']}</td>
                            <td>{$row['item_cat']}</td>
                            <td>{$row['item_detail']}</td>
                        </tr>";
                        $i++;
                    }

                    echo '</tbody>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<?php require_once './includes/footer.php'; ?>
