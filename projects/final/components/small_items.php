<?php

function renderListItem(int $id, string $display_text, string $edit_link, string $view_link)
{
    $image_link = "./assets/image_edit.png";

    print("
        <div style='display: flex; flex-direction: row; align-items: center; margin-top: 8px;'>
            <p class='books-app-text books-app-menu'><a href='$view_link.php?id=$id'>> $display_text</a></p>
          
            <a href='$edit_link.php?id=$id' style='margin-left: 12px; margin-top: 4px;'>
                <img src='$image_link' alt='edit' style='width: 20px; height: 20px'/>
            </a>
        </div>
    ");
}

function renderAddButton(string $go_to_page, string $entity_name = '', string $type = 'Add', string $image_link = "./assets/image_add.png")
{
    print("
        <div class='add-button separate-link'>
            <img src='$image_link' alt='edit' style='width: 20px; height: 20px; margin-right: 8px;'/>
            
            <p class='books-app-text books-app-menu add-text'><a href='$go_to_page'>$type $entity_name</a></p>
        </div>
    ");
}