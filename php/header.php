<?php
// ... (session start and db connect)

// ... (fetch menu items logic)

// Fetch header settings
$sql_settings = "SELECT * FROM header_settings";
$header_settings = [];
if($result_settings = mysqli_query($link, $sql_settings)){
    while($row_setting = mysqli_fetch_assoc($result_settings)){
        $header_settings[$row_setting['setting_name']] = $row_setting['setting_value'];
    }
    mysqli_free_result($result_settings);
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
<head>
    <!-- ... (head content) -->
</head>
<body>
    <header style="<?php echo $header_style; ?>">
        <!-- ... (header content) -->
        <nav class="main-nav">
            <!-- ... (main menu) -->
            <ul id="main-menu">
                <!-- ... (dynamic menu items) -->
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Editor in Chief"): ?>
                        <li><a href="manage_menu.php">Manage Menu</a></li>
                        <li><a href="customize_header.php">Customize Header</a></li>
                    <?php endif; ?>
                    <!-- ... (other user-specific links) -->
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <script src="js/main.js"></script>
</body>
</html>
