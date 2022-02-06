<?php
    require_once('./helpers.php');

    $floats = getParam('floats');
    $bands = getParam('bands');

    $error_message = '';

    $floats_error = checkInput($floats, 'floats');
    $bands_error = checkInput($bands, 'bands');

    $error_message = $error_message . $floats_error . ((!empty($floats_error) && !empty($bands_error)) ? "\n" : '') . $bands_error;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parade Length - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <h1>Parade Length</h1>

    <?php if ($error_message === ''): ?>
        <p>Floats: <?php echo($floats) ?></p>

        <p>Bands: <?php echo($bands) ?></p>

        <p>Length: <?php echo($floats * 30 + $bands * 100) ?> meters</p>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error_message) ?></p>
        </div>
    <?php endif; ?>
</body>
</html>