<?php
    function extractName(string $file): string
    {
        return(ucwords(str_replace('_', ' ', $file)));
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<title>
			Phat Pham - G00843027
		</title>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" type="text/css" href="index.css">
	</head>

	<body>
        <h1>Hi, I'm Phat!</h1>

		<div class="main-container">
            <div class="child-container">
                <h3>Browse the list of Exercies below</h3>

                <?php
                $files = scandir('./exercises/');
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        echo '<p></p><a href="'.'./exercises/'.basename($file).'/'.'">'.extractName(basename($file)).'</a></p>';
                    }
                }
                ?>
            </div>

            <div class="child-container">
                <h3>Browse the list of Database Exercises below</h3>

                <?php
                $files = scandir('./database_exercises/');
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        echo '<p></p><a href="'.'./database_exercises/'.$file.'">'.extractName(basename($file)).'</a></p>';
                    }
                }
                ?>
            </div>

            <div class="child-container">
                <h3>Browse the list of Projects below</h3>

                <?php
                $files = scandir('./projects/');
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        echo '<p></p><a href="'.'./projects/'.$file.'">'.extractName(basename($file)).'</a></p>';
                    }
                }
                ?>
            </div>
		</div>
	</body>
</html>
