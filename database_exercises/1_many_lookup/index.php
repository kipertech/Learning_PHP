<?php
    // Load helpers
    require_once(__DIR__ . '/../../utils/util_params.php');
    require_once(__DIR__ . '/../../utils/util_database.php');

    $start = microtime(true);

    // Initialize values
    $total_time = 0;
    $row = [];
    $error = '';

    // Get input from param
    $input_name = getParam('name');
    $error = checkStringInput($input_name, 'Product Name (Param Name: "name")');

    // If input is valid
    if (empty($error)) {
        // Connect to DB
        $dbObject = getDBConnection();
        $mysqli = $dbObject -> mysqli;
        $error = $dbObject -> connection_error;

        // If successfully connected to DB
        if (empty($error)) {
            // Perform query
            $query = "
                SELECT *
                FROM products INNER JOIN
                     categories ON products.CategoryID = categories.CategoryID INNER JOIN
                     suppliers ON products.SupplierID = suppliers.SupplierID
                WHERE ProductName = ?;
            ";
            $row = simpleQueryFetch($mysqli, $query, $input_name, true, true, true);

            // If ID is not found
            if (empty($row)) {
                $error = ' • No result was found for the input name: ' . $input_name;
            }

            // Log execution time
            $total_time = number_format(microtime(true) - $start, 2, '.', ',');
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <?php
        $page_title = "1:N Lookup Test";
        require_once(__DIR__ . '/../../components/head.php');
    ?>
</head>

<body>
    <h1>1:N Lookup</h1>

    <!-- Error Check -->
    <?php if ($error === ''): ?>
        <!-- Connection Info -->
        <div class="database-info-container">
            <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>
            <p class="database-info">Data Source: <span class="purple-text"><a href="https://github.com/AndrejPHP/w3schools-database">W3School's Sample Database</a></span></p>
            <p>---</p>
            <p class="database-info">Input Name: <span class="purple-text"><?php print($input_name) ?></span></p>
            <p class="database-info">Execution Time: <span class="purple-text"><?php print($total_time) ?> seconds</span></p>
        </div>

        <!-- Main Container -->
        <div class="main-container">
            <h3>Product Detail</h3>
            <p class="gray-text">Product ID: <span class="black-text"><?php print($row['ProductID']) ?></span></p>
            <p class="gray-text">Product Name: <span class="black-text"><?php print($row['ProductName']) ?></span></p>
            <p class="gray-text">Unit: <span class="black-text"><?php print($row['Unit']) ?></span></p>
            <p class="gray-text">Price: <span class="black-text"><?php print($row['Price']) ?></span></p>

            <hr class="short-divider"/>

            <h3>Product's Category Detail</h3>
            <p class="gray-text">Category ID: <span class="black-text"><?php print($row['CategoryID']) ?></span></p>
            <p class="gray-text">Category Name: <span class="black-text"><?php print($row['CategoryName']) ?></span></p>
            <p class="gray-text">Description: <span class="black-text"><?php print($row['Description']) ?></span></p>

            <hr class="short-divider"/>

            <h3>Product's Supplier Detail</h3>
            <p class="gray-text">Supplier ID: <span class="black-text"><?php print($row['SupplierID']) ?></span></p>
            <p class="gray-text">Supplier Name: <span class="black-text"><?php print($row['SupplierName']) ?></span></p>
            <p class="gray-text">Contact Name: <span class="black-text"><?php print($row['ContactName']) ?></span></p>
            <p class="gray-text">Address: <span class="black-text"><?php print($row['Address'] . ', ' . $row['City'] . ', ' . $row['Country'] . ', ' . $row['PostalCode']) ?></span></p>
            <p class="gray-text">Phone: <span class="black-text"><?php print($row['Phone']) ?></span></p>
        </div>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Footer Text -->
    <?php require_once(__DIR__ . '/../../components/footer.php') ?>
</body>
</html>