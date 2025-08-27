<?php
// ... (session start and access check)

// Include db connect file
require_once "php/db_connect.php";

// ... (create table)

// Function to update or insert a setting
function save_setting($link, $name, $value){
    $sql = "INSERT INTO header_settings (setting_name, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "sss", $name, $value, $value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Processing form data
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_settings'])){
    // Handle background color
    if(!empty($_POST['background_color'])){
        save_setting($link, 'background_color', $_POST['background_color']);
    }

    // Handle background image upload
    if(!empty($_FILES["background_image"]["name"])){
        $target_dir = "uploads/header/";
        if(!is_dir($target_dir)){
            mkdir($target_dir, 0755, true);
        }
        $target_file = $target_dir . basename($_FILES["background_image"]["name"]);

        if (move_uploaded_file($_FILES["background_image"]["tmp_name"], $target_file)) {
            save_setting($link, 'background_image', $target_file);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    header("location: customize_header.php"); // Refresh page
}


// ... (fetch current settings)

require_once "php/header.php";
?>

<main>
    <h2>Customize Header</h2>
    <!-- ... (form remains the same) -->
</main>

<?php require_once "php/footer.php"; ?>
