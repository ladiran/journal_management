<?php
// Include db connect file
require_once "php/db_connect.php";

// --- Create Tables ---
$create_tables_sql = "
CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(191) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Reader',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS reviewer_invitations (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(191) NOT NULL,
    token VARCHAR(191) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL
);

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
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (section_editor_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS manuscript_reviewers (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    manuscript_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    manuscript_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    comments_for_editor TEXT,
    comments_for_author TEXT,
    recommendation VARCHAR(50),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
);
";

if (!$link->multi_query($create_tables_sql)) {
    die("Error creating tables: " . $link->error);
}
// To clear the results of the multi_query
while ($link->next_result()) {;}

// --- Seed Users ---
// ... (rest of the script is the same)
?>
