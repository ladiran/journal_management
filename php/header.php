<?php
// Initialize the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Use __DIR__ to create a reliable path to db_connect.php
require_once __DIR__ . "/db_connect.php";

// Fetch menu items
$sql_menu = "SELECT * FROM menu_items ORDER BY parent_id, item_order";
$menu_items = [];
if($result_menu = mysqli_query($link, $sql_menu)){
    while($row_menu = mysqli_fetch_assoc($result_menu)){
        $menu_items[] = $row_menu;
    }
    mysqli_free_result($result_menu);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header style="<?php echo $header_style; ?>">
        <div class="header-top">
            <div class="logo">
                <a href="index.php"><img src="images/logo_placeholder.png" alt="Journal Logo" style="width: 100px; height: auto;"></a>
            </div>
            <div class="journal-title">
                <h1>Journal Management System</h1>
            </div>
            <nav class="user-nav">
                <ul>
                    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <li><a href="profile.php">Profile (<?php echo htmlspecialchars($_SESSION["username"]); ?>)</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
        <nav class="main-nav">
            <button class="menu-toggle" aria-expanded="false" aria-controls="main-menu">
                <span class="sr-only">Open main menu</span>
                <span class="hamburger-icon"></span>
            </button>
            <ul id="main-menu">
                <?php foreach($menu as $item): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($item['link']); ?>"><?php echo htmlspecialchars($item['title']); ?></a>
                        <?php if(!empty($item['children'])): ?>
                            <ul class="sub-menu">
                                <?php foreach($item['children'] as $child): ?>
                                    <li><a href="<?php echo htmlspecialchars($child['link']); ?>"><?php echo htmlspecialchars($child['title']); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <?php if($_SESSION["role"] === "Admin" || $_SESSION["role"] === "Editor in Chief"): ?>
                        <li><a href="manage_menu.php">Manage Menu</a></li>
                        <li><a href="customize_header.php">Customize Header</a></li>
                    <?php endif; ?>
                    <?php if($_SESSION["role"] === "Author"): ?>
                        <li><a href="submit_manuscript.php">Submit Manuscript</a></li>
                        <li><a href="manuscript_status.php">Manuscript Status</a></li>
                    <?php endif; ?>
                    <?php
                        $editor_roles = ["Editor in Chief", "Assistant Editor in Chief", "Section Editor"];
                        if(in_array($_SESSION["role"], $editor_roles)):
                    ?>
                        <li><a href="editor_dashboard.php">Editor Dashboard</a></li>
                    <?php endif; ?>
                    <?php if($_SESSION["role"] === "Reviewer"): ?>
                        <li><a href="reviewer_dashboard.php">Reviewer Dashboard</a></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <script src="js/main.js"></script>
</body>
</html>
