<?php
// ... (db connect)

// --- Create Tables ---
$create_tables_sql = "
-- ... (other tables) ...

CREATE TABLE IF NOT EXISTS header_settings (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    setting_name VARCHAR(191) NOT NULL UNIQUE,
    setting_value VARCHAR(255)
);
";

// ... (rest of the script)
?>
