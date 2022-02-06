<?php
    require_once('./helpers.php');

    // Init error message
    $error_message = '';

    // Validate numeric inputs
    $goats = getParamFromGet('goats');
    $goats_error = checkInput($goats, 'Goats', 1);

    $course_length = getParamFromGet('course_length');
    $course_length_error = checkInput($course_length, 'Course Length', 10);

    // Validate Hurdles
    $hurdles = getParamFromGet('hurdles');
    $hurdles_error = checkHurdles($hurdles);

    // Final error message
    $error_message = $error_message .
        $goats_error .
        (!empty($goats_error) ? "\n" : '') .
        $course_length_error .
        ((!empty($course_length_error) || !empty($goats_error)) ? "\n" : '') .
        $hurdles_error;

    // Calculation
    $kilo_calories = 0;
    if ($error_message == '') {
        // Compute kilocalories.
        $kilo_calories = $goats * $course_length * 100 / 1000;

        // Put Hurdles calculation in
        $hurdles = strtolower(trim($hurdles));
        if ($hurdles == 'y') {
            $kilo_calories *= 2;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Goat Fuel - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <h1>Goat Fuel</h1>

    <?php if ($error_message === ''): ?>
        <p>Goats: <?php echo($goats) ?></p>

        <p>Course Length: <?php echo($course_length) ?></p>

        <p>Hurdles: <?php echo($hurdles === 'y' ? 'Yes' : 'No') ?></p>

        <p>Kilocalories: <?php echo($kilo_calories) ?>
            <?php
                if ($kilo_calories > 100) {
                    print("<span class='error-message bold-text'>(Better call Euphonites)</span>");
                }
            ?>
        </p>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">One or more errors have occurred:</p>
            <p class="error-message"><?php echo($error_message) ?></p>
        </div>
    <?php endif; ?>
</body>
</html>