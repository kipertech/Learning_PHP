<!-- Check if user forgot to put in the variables on the URL -->
<?php
    $dogs = 1;
    $pups = 1;

    if(isset($_GET['dogs']) && !empty($_GET['dogs'])) {
        $dogs = $_GET['dogs'];
    }
    else echo '<p style="color:red">Number of dogs is not set, using 1 as default value.</p>';

    if(isset($_GET['pups']) && !empty($_GET['pups'])) {
        $pups = $_GET['pups'];
    }
    else echo '<p style="color:red">Number of pups is not set, using 1 as default value.</p>';

    // Calculations
    $food = ($dogs * 28) + ($pups * 14);
    $drinks = ($dogs * 19) + ($pups * 32);
    $games = ($dogs * 18) + ($pups * 9);
    $gifts = ($dogs * 12) + ($pups * 29);
    $clean = ($dogs * 17) + ($pups * 58);
    $party_total = $food + $drinks + $games + $gifts + $clean;
    $gratuity = $party_total * 15 / 100;
    $event_total = $party_total + $gratuity;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Party Planner - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <h1>Party Planner</h1>

    <h2>Invitations</h2>
    <p>Dogs: <?php print $dogs ?></p>
    <p>Pups: <?php print $pups ?></p>

    <h2>Cost</h2>

    <p>Food: $<?php print $food ?></p>
    <p>Drinks: $<?php print $drinks ?></p>
    <p>Games: $<?php print $games ?></p>
    <p>Gifts: $<?php print $gifts ?></p>
    <p>Clean Up: $<?php print $clean ?></p>
    <p style="color:blue">Party Total: $<?php print $party_total ?></p>
    <p style="color:orange">Gratuity Total: $<?php print $gratuity ?></p>
    <p style="color:green">Event Total: $<?php print $event_total ?></p>

</body>
</html><?php
