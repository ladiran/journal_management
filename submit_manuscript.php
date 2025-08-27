<?php
// Initialize the session
session_start();

// Check if the user is logged in and is an Author
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "Author"){
    header("location: login.php");
    exit;
}

// ... (db connect and table creation)

// ... (form processing)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Manuscript</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 500px; padding: 20px; margin: auto; }
    </style>
</head>
<body>
    <header>
        <h1>Journal Management System - Submit Manuscript</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="wrapper">
        <h2>Submit Your Manuscript</h2>
        <p>Please fill out the form below to submit your manuscript for review.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" value="<?php echo $title; ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label for="abstract">Abstract</label>
                <textarea id="abstract" name="abstract" class="form-control"><?php echo $abstract; ?></textarea>
                <span class="help-block"><?php echo $abstract_err; ?></span>
            </div>
            <div class="form-group">
                <label for="keywords">Keywords</label>
                <input type="text" id="keywords" name="keywords" class="form-control" value="<?php echo $keywords; ?>">
                <span class="help-block"><?php echo $keywords_err; ?></span>
            </div>
            <div class="form-group">
                <label for="manuscript_file">Manuscript File (DOC, DOCX, or PDF)</label>
                <input type="file" id="manuscript_file" name="manuscript_file" class="form-control">
                <span class="help-block"><?php echo $file_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
