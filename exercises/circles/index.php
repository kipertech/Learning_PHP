<!-- Check if user forgot to put in the radius on the URL -->
<?php
    $radius = 1;
    $pi = 3.14159;

    if(isset($_GET['radius']) && !empty($_GET['radius'])) {
        $radius = $_GET['radius'];
    }
    else {
        echo '<script>alert("Radius is not set, using 1 as default value.")</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Circles - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> </head>
<body>
    <h1>Circles</h1>

    <p>
        Input Radius: <?php print $radius; ?>
    </p>

    <!-- C=2*PI*r -->
    <p>
        Circumference:
        <?php print (2 * $pi * $radius); ?>
    </p>

    <!-- A=PI*(r^2) -->
    <p>
        Area:
        <?php print ($pi * $radius * $radius); ?>
    </p>
</body>
</html>