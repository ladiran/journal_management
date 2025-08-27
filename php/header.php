<?php
// Initialize the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Use __DIR__ to create a reliable path to db_connect.php
require_once __DIR__ . "/db_connect.php";

// Fetch menu items only if the table exists
$menu_items = [];
$menu_table_exists_sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'menu_items' LIMIT 1";
$menu_table_exists_result = mysqli_query($link, $menu_table_exists_sql);

if($menu_table_exists_result && mysqli_num_rows($menu_table_exists_result) > 0){
    $sql_menu = "SELECT * FROM menu_items ORDER BY parent_id, item_order";
    if($result_menu = mysqli_query($link, $sql_menu)){
        while($row_menu = mysqli_fetch_assoc($result_menu)){
            $menu_items[] = $row_menu;
        }
        mysqli_free_result($result_menu);
    }
}

function build_menu(array $elements, $parentId = 0) {
    // ... (build_menu function is the same)
}
$menu = build_menu($menu_items);

// Fetch header settings only if the table exists
$header_settings = [];
$settings_table_exists_sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'header_settings' LIMIT 1";
$settings_table_exists_result = mysqli_query($link, $settings_table_exists_sql);

if($settings_table_exists_result && mysqli_num_rows($settings_table_exists_result) > 0){
    $sql_settings = "SELECT * FROM header_settings";
    if($result_settings = mysqli_query($link, $sql_settings)){
        while($row_setting = mysqli_fetch_assoc($result_settings)){
            $header_settings[$row_setting['setting_name']] = $row_setting['setting_value'];
        }
        mysqli_free_result($result_settings);
    }
}

$header_style = '';
if(!empty($header_settings['background_color'])){
    $header_style .= "background-color: " . htmlspecialchars($header_settings['background_color']) . ";";
}
if(!empty($header_settings['background_image'])){
    $header_style .= "background-image: url('" . htmlspecialchars($header_settings['background_image']) . "'); background-size: cover; background-position: center;";
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- ... (HTML is the same) -->
</html>
