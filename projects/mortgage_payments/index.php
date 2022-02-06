<?php
    require_once('./helpers.php');

    // Init error message
    $error_message = '';

    // Validate numeric inputs
    $principal = getParam('principal');
    $principal_error = checkInput($principal, 'Principal', 1, 999999999);

    $rate = getParam('rate');
    $rate_error = checkInput($rate, 'Interest Rate', 0.01, 19.99, true);

    $term = getParam('term');
    $term_error = checkInput($term, 'Term (Years)', 1, 49);

    // Final error message
    $error_message =
        $principal_error .
        (!empty($principal_error) ? "\n" : '') .
        $rate_error .
        ((!empty($rate_error) || !empty($principal_error)) ? "\n" : '') .
        $term_error;

    // Calculation
    if ($error_message === '') {
        $mortgage_payment = 0;
        $principal = (int)$principal;
        $total_months = (int)$term * 12;
        $actual_rate = (float)$rate / 100 / 12;

        $mortgage_payment =
            $principal *
            (
                ($actual_rate * pow((1 + $actual_rate), $total_months))
                /
                (pow((1 + $actual_rate), $total_months) - 1)
            );

        // Recommendation message
        $is_high = $mortgage_payment > 2000;
        $doggo_image = $is_high ? './images/one-doggo.png' : './images/two-doggo.png';
        $doggo_message = 'You should get ' . ($is_high ? 'a' : 'two') . ' doggo' . ($is_high ? '' : 's');

        // Format for nice looking number
        $mortgage_payment = number_format((float)$mortgage_payment, 2, '.', ',');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mortgage Payments - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <!-- Main View -->
    <div>
        <div class="main-container">
            <!-- Header -->
            <div class="header-container">
                <h1 class="header-text">Mortgage Payment</h1>
                <h4 class="gray-text">Calculator</h4>
            </div>

            <!-- Main Content -->
            <?php if ($error_message === ''): ?>
                <p >Principal: <span class="content-text">$<?php echo(number_format((float)$principal, 0, '', ',')) ?></span></p>

                <p>Annual Interest Rate (in %, e.g. 3.15): <span class="content-text"><?php echo($rate) ?>%</span></p>

                <p>Term (in Years, e.g. 30): <span class="content-text"><?php echo($term) ?></span></p>

                <hr class="short-divider">

                <p>Your Monthly Payment</p>

                <div class="result-box">
                    <h1 class="result-text">$<?php echo($mortgage_payment) ?></h1>
                </div>

                <hr class="short-divider">

                <div class="doggo-view">
                    <p class="doggo-message"><?php echo($doggo_message) ?></p>

                    <img class="doggo-image" src="<?php echo($doggo_image)?>" alt="doggo_img"/>
                </div>
            <?php else: ?>
                <div class="error-view">
                    <h3 class="error-message bold-text">One or more errors have occurred:</h3>
                    <p class="error-message spacy-text"><?php echo($error_message) ?></p>
                </div>
            <?php endif; ?>

            <!-- Footer Text -->
            <p class="footer-text">A masterpiece by Phat Pham.
                Made with ♥ in 2022.</p>
        </div>
    </div>
</body>
</html>