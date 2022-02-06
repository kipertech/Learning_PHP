<!-- Check if user forgot to put in animal type on the URL -->
<?php
    echo $_SERVER['RDS_HOSTNAME'];

    $animal_type = '';
    $animal_list = [
        0 => 'dog',
        1 => 'cat',
        2 => 'pig'
    ];
    $animal_img_list = [
        0 => './images/dog.jpg',
        1 => './images/cat.jpg',
        2 => './images/pig.jpg'
    ];
    $error_message = '';

    if (isset($_GET['animal_type']) && !empty($_GET['animal_type'])) {
        $animal_type = $_GET['animal_type'];

        // Check if chosen animal is in the list
        if (!in_array($animal_type, $animal_list)) {
            $error_message = 'Animal is not in our stack, but we will update our database (soon, we think)!';
            $animal_type = '';
        }
        else $animal_type = strtolower($_GET['animal_type']);
    }
    else $error_message = 'No animal was chosen';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Images - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <h1>Animal Lookup</h1>

    <?php if ($animal_type !== ''): ?>
        <p>Chosen Animal: <span class="chosen-animal"><?php print(strtoupper($animal_type)); ?></span></p>

        <img
            class="animal-image"
            src="<?php print(
                array_values($animal_img_list)[
                    array_search($animal_type, $animal_list)
                ]
            ); ?>"
            alt="<?php echo('img_' . $animal_type); ?>"
        />
    <?php else: ?>
        <div class="error-view">
            <p class="error-message"><?php print($error_message) ?></p>
        </div>
    <?php endif; ?>
</body>
</html>