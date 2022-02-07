<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php print $page_title; ?> | Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../style_general.css">
</head>

<body>
    <!-- Title -->
    <h1><?php print($page_title) ?></h1>

    <!-- Nav Bar -->

    <!-- Error Check -->
    <?php if (($error ?? '') === ''): ?>
        <!-- Connection Info -->
        <div class="database-info-container">
            <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>
            <p class="database-info">Data Source: <span class="purple-text"><a href="https://github.com/AndrejPHP/w3schools-database">W3School's Sample Database</a></span></p>
            <p>---</p>
            <p class="database-info">Input Data: <span class="purple-text"><?php print($input_data ?? '(None)') ?></span></p>
            <p class="database-info">Execution Time: <span class="purple-text"><?php print($total_time ?? '0.00') ?> seconds</span></p>
        </div>

        <!-- Main Content -->
        <div class="main-container">
            <?php print($page_body); ?>
        </div>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Footer Text -->
    <p class="footer-text">A masterpiece by Phat Pham.
        Made with ♥ in 2022.</p>
</body>
</html>