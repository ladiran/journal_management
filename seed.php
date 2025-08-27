<?php
// Include db connect file
require_once "php/db_connect.php";

// --- Create Tables ---
$create_tables_sql = "
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Reader',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reviewer_invitations (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(191) NOT NULL,
    token VARCHAR(191) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS manuscripts (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    author_id INT NOT NULL,
    section_editor_id INT,
    title VARCHAR(255) NOT NULL,
    abstract TEXT NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    filepath VARCHAR(255) NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'Submitted',
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (section_editor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS manuscript_reviewers (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    manuscript_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reviews (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    manuscript_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    comments_for_editor TEXT,
    comments_for_author TEXT,
    recommendation VARCHAR(50),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS menu_items (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    parent_id INT,
    title VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    item_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS header_settings (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    setting_name VARCHAR(191) NOT NULL UNIQUE,
    setting_value VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";

if ($link->multi_query($create_tables_sql)) {
    do {
        // Store first result set
        if ($result = $link->store_result()) {
            $result->free();
        }
    } while ($link->next_result());
} else {
    die("Error creating tables: " . $link->error);
}

echo "Tables created successfully.\n";

// --- Seed Users ---
$users = [
    ['username' => 'admin', 'password' => 'password', 'email' => 'admin@example.com', 'role' => 'Admin'],
    ['username' => 'eic', 'password' => 'password', 'email' => 'eic@example.com', 'role' => 'Editor in Chief'],
    ['username' => 'author', 'password' => 'password', 'email' => 'author@example.com', 'role' => 'Author'],
    ['username' => 'section_editor', 'password' => 'password', 'email' => 'se@example.com', 'role' => 'Section Editor'],
    ['username' => 'reviewer', 'password' => 'password', 'email' => 'reviewer@example.com', 'role' => 'Reviewer'],
];

$sql_user = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
foreach ($users as $user) {
    $check_sql = "SELECT id FROM users WHERE username = ? OR email = ?";
    if($check_stmt = mysqli_prepare($link, $check_sql)){
        mysqli_stmt_bind_param($check_stmt, "ss", $user['username'], $user['email']);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if(mysqli_stmt_num_rows($check_stmt) == 0){
            if($stmt = mysqli_prepare($link, $sql_user)){
                $hashed_password = password_hash($user['password'], PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "ssss", $user['username'], $user['email'], $hashed_password, $user['role']);
                if(mysqli_stmt_execute($stmt)){
                    echo "User '{$user['username']}' created successfully.\n";
                }
            }
        }
        mysqli_stmt_close($check_stmt);
    }
}

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
