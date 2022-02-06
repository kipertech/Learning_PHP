<!-- Check if user forgot to put in the meal cost on the URL -->
<?php
    $meal_cost = 10;
    $tip_amount = [10, 15, 22];

    $total_tip = 0;
    $total_cost = 0;

    if(isset($_GET['meal_cost']) && !empty($_GET['meal_cost'])) {
        $meal_cost = $_GET['meal_cost'];
        $total = $meal_cost;
    }
    else {
        echo '<script>alert("Meal cost is not set, using $10 as default value.")</script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tips - Phat Pham</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body>
    <h1>Tips</h1>
    <p>Input Meal Cost: $<?php print $meal_cost; ?></p>

    <?php foreach($tip_amount as $key => $value): ?>
        <h2>Tip: <?php print $value; ?>%</h2>
        <?php
            $tip = ($meal_cost * $value / 100);
            $total = $meal_cost + $tip;
            $total_tip += $tip;
            $total_cost += $total;
        ?>

        <!-- Print out result -->
        <p>Tip: $<?php print $tip; ?></p>
        <p>Total: $<?php print $total; ?></p>
    <?php endforeach; ?>

    <h2>Average</h2>
    <?php $item_count = count($tip_amount); ?>
    <p>Tip: $<?php print ($total_tip / $item_count); ?></p>
    <p>Total: $<?php print ($total_cost / $item_count); ?></p>
</body>
</html>