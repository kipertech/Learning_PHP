<?php

// Input params
$page_title = "Pokemon Petting Zoo Tickets";
$hide_db_info = true;

// Define HTML Body
ob_start(); ?>
    <p>Workout Prices for a Group</p>

    <div class="big-form-container">
        <!-- Form -->
        <form method="post" action="result.php">
            <div class="horizontal-container">
                <!-- Adults -->
                <div class="small-form-container space-right">
                    <label>Adults</label>
                    <input type="number" name="adults" min="0">
                    <small>Number of adults in the group</small>
                </div>

                <!-- Children -->
                <div class="small-form-container space-left">
                    <label>Children</label>
                    <input type="number" name="children" min="0">
                    <small>Number of children in the group</small>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <button type="submit">Compute Price</button>
            </div>
        </form>
    </div>

<?php $page_body = ob_get_clean();

// Import HTML Template
require_once('../../components/page_template.php');