<?php

// Import Pokemon List
require_once('./pokemon_list.php');

// Define Params
$page_title = "Pokemon Evolution";
$hide_db_info = true;
$pokemon_list = getPokemonList();

// Define HTML Body
ob_start(); ?>

<p>Choose one or more Pokémon to see their evolutions</p>

<div class="big-form-container">
    <form method="post" action="result.php">
        <select class="drop-down-list" name="pokemon-list[]" multiple size="<?php print(count($pokemon_list) + 1); ?>">
            <option value="none" selected>(None)</option>
            <?php
                foreach ($pokemon_list as $element) {
                    print("<option value=$element->id>$element->name</option>");
                }
            ?>
        </select>

        <!-- Submit Button -->
        <div class="button-container">
            <button type="submit">Go!</button>
        </div>
    </form>
</div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');