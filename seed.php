<?php
// ... (db connect)

// --- Create Tables ---
$create_tables_sql = "
-- ... (users, reviewer_invitations, manuscripts, manuscript_reviewers, reviews tables) ...

CREATE TABLE IF NOT EXISTS menu_items (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    parent_id INT,
    title VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    item_order INT NOT NULL DEFAULT 0
);

CREATE TABLE IF NOT EXISTS header_settings (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    setting_name VARCHAR(255) NOT NULL UNIQUE,
    setting_value VARCHAR(255)
);
";

// ... (execute multi_query)

// --- Seed Users ---
// ... (seed users logic)

// --- Seed Menu Items ---
$menu_items_to_seed = [
    ['title' => 'About the Journal', 'link' => '#', 'parent_id' => NULL],
    ['title' => 'Journal Indexing', 'link' => '#', 'parent_id' => NULL],
    ['title' => 'Editorial Board', 'link' => '#', 'parent_id' => NULL],
    ['title' => 'Advisory Board', 'link' => '#', 'parent_id' => NULL],
    ['title' => 'Editorial Policy', 'link' => '#', 'parent_id' => NULL],
];

$sql_menu = "INSERT INTO menu_items (title, link, parent_id) VALUES (?, ?, ?)";
foreach ($menu_items_to_seed as $item) {
    $check_sql = "SELECT id FROM menu_items WHERE title = ?";
    if($check_stmt = mysqli_prepare($link, $check_sql)){
        mysqli_stmt_bind_param($check_stmt, "s", $item['title']);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) == 0){
            if($stmt = mysqli_prepare($link, $sql_menu)){
                mysqli_stmt_bind_param($stmt, "ssi", $item['title'], $item['link'], $item['parent_id']);
                if(mysqli_stmt_execute($stmt)){
                    echo "Menu item '{$item['title']}' created successfully.\n";
                }
            }
        }
    }
}

echo "Seeding complete.\n";
mysqli_close($link);
?>
