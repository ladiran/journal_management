<?php
// Initialize the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Use __DIR__ to create a reliable path to db_connect.php
require_once __DIR__ . "/db_connect.php";

// Fetch menu items
// ... (rest of the php code for fetching menu and header settings)

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header style="<?php echo $header_style; ?>">
        <!-- ... (header content) -->
    </header>
    <script src="js/main.js"></script>
</body>
</html>
