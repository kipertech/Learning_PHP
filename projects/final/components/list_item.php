<?php

function renderListItem(int $id, string $display_text, string $edit_link, string $view_link)
{
    $image_link = "./assets/image_edit.png";

    print("
        <div style='display: flex; flex-direction: row; align-items: center;'>
            <p class='books-app-text books-app-menu'><a href='$view_link.php?id=$id'>> $display_text</a></p>
          
            <a href='$edit_link.php' style='margin-left: 8px;'>
                <img src='$image_link' alt='edit' style='width: 32px; height: 32px'/>
            </a>
        </div>
    ");
}