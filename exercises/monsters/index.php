<!-- Check if user forgot to put in parameters on the URL -->
<?php
    $monster_type = '';
    $monster_list = ['santa', 'cthulhu'];
    $tone = '';
    $tone_list = ['warning', 'hint'];
    $error_message = '';

    // Check if monster was input
    if (isset($_GET['monster']) && !empty($_GET['monster'])) {
        $monster_type = strtolower($_GET['monster']);

        // Check if chosen monster is in the list
        if (!in_array($monster_type, $monster_list)) {
            $error_message = ' • Monster is not in our list.';
            $monster_type = '';
        }
    }
    else $error_message = ' • No monster was chosen.';

    // Check if tone was input
    $break_line = !empty($error_message) ? "\n" : '';
    if (isset($_GET['tone']) && !empty($_GET['tone'])) {
        $tone = strtolower($_GET['tone']);

        // Check if chosen tone is in the list
        if (!in_array($tone, $tone_list)) {
            $error_message .= $break_line . ' • Tone is not in our list.';
            $tone = '';
        }
    }
    else $error_message .= $break_line . ' • No tone was chosen.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monsters - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <h1>Monsters</h1>

    <?php if ($error_message === ''): ?>
        <p>Big ol pupper you are doing me a frighten. Doggo boof vvv such treat pats, porgo shoober borking doggo.</p>

        <div class="main-container <?php echo($tone) ?>-border">
            <h2 class="<?php echo($tone) ?>-text"><?php echo(ucfirst($tone)) ?></h2>

            <div class="content-container">
                <img
                    class="monster-image <?php echo($tone) ?>-border"
                    src="<?php print('./images/' . $monster_type . '.png'); ?>"
                    alt="<?php echo('img_' . $monster_type); ?>"
                />

                <p class="content-text">
                    Sub woofer bork puggorino long bois, snoot blop. Puggo blep the neighborhood pupper. Aqua doggo waggy wags, you are doing me a frighten boof.

                    Puggo blep the neighborhood pupper. Aqua doggo waggy wags, you are doing me a frighten boof.

                    Big ol pupper you are doing me a frighten. Doggo boof vvv such treat pats, porgo shoober borking doggo.
                </p>
            </div>
        </div>

        <!-- Footer Text -->
        <p>Puggo blep the neighborhood pupper. Aqua doggo waggy wags, you are doing me a frighten boof.</p>
    <?php else: ?>
        <div class="error-view">
            <p class="error-message bold-text">Missing input parameter(s):</p>
            <p class="error-message"><?php echo($error_message) ?></p>
        </div>
    <?php endif; ?>
</body>
</html>