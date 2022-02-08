<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo($page_title ?? '(No Title)'); ?> | Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="../style_general.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Gideon+Roman&family=Noto+Serif:wght@400;700&family=Quintessential&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Title -->
    <?php if (!isset($hide_page_title) || ($hide_page_title !== true)): ?>
        <h1><?php echo($page_title ?? '(No Title)') ?></h1>
    <?php endif; ?>

    <!-- Error Check -->
    <?php if (($error ?? '') === ''): ?>
        <!-- Connection Info -->
        <?php if (!isset($hide_db_info) || ($hide_db_info !== true)): ?>
            <div class="database-info-container">
                <!-- Hostname -->
                <p class="database-info">Database Host: <span class="purple-text"><?php print($_SERVER['RDS_HOSTNAME']) ?></span></p>

                <!-- Data Source Info -->
                <?php if (isset($data_source)): ?>
                    <p class="database-info">Data Source: <span class="purple-text"><a href="<?php echo($data_source_url ?? 'https://webapps.skilling.us/') ?>"><?php echo($data_source) ?></a></span></p>
                <?php else: ?>
                    <p class="database-info">Data Source: <span class="purple-text"><a href="https://github.com/AndrejPHP/w3schools-database">W3School's Sample Database</a></span></p>
                <?php endif; ?>

                <!-- Input Data and Execution Time -->
                <p>---</p>
                <p class="database-info">Input Data: <span class="purple-text"><?php print($input_data ?? '(None)') ?></span></p>
                <p class="database-info">Execution Time: <span class="purple-text"><?php print($total_time ?? '0.00') ?> seconds</span></p>
            </div>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="main-container">
            <?php print($page_body ?? "<p>(Empty Page)</p>"); ?>
        </div>
    <?php else: ?>
        <!-- Error View -->
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error ?? '(No Error)') ?></p>
        </div>
    <?php endif; ?>

    <!-- Footer Text -->
    <p class="footer-text">A masterpiece by Phat Pham.
        Made with ♥ in 2022.</p>
</body>
</html>