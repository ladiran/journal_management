<?php
// Initialize the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Use __DIR__ to create a reliable path to db_connect.php
require_once __DIR__ . "/db_connect.php";

// Fetch menu items only if the table exists
$menu_items = [];
$table_exists_sql = "SELECT 1 FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'menu_items' LIMIT 1";
$table_exists_result = mysqli_query($link, $table_exists_sql);

if($table_exists_result && mysqli_num_rows($table_exists_result) > 0){
    $sql_menu = "SELECT * FROM menu_items ORDER BY parent_id, item_order";
    if($result_menu = mysqli_query($link, $sql_menu)){
        while($row_menu = mysqli_fetch_assoc($result_menu)){
            $menu_items[] = $row_menu;
        }
        mysqli_free_result($result_menu);
    }
}

function build_menu(array $elements, $parentId = 0) {
    $branch = array();
    foreach ($elements as $element) {
        if ($element['parent_id'] == $parentId) {
            $children = build_menu($elements, $element['id']);
            if ($children) {
                $element['children'] = $children;
            }
            $branch[] = $element;
        }
    }
    return $branch;
}

$menu = build_menu($menu_items);

// ... (rest of the file is the same)
?>
<!-- ... (HTML is the same) -->
