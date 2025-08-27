<?php
// Initialize the session
session_start();

// Check if the user is logged in and is a Reviewer
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "Reviewer"){
    header("location: login.php");
    exit;
}

// Include db connect file
require_once "php/db_connect.php";

// Create reviews table if it doesn't exist
$create_table_sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    manuscript_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    comments_for_editor TEXT,
    comments_for_author TEXT,
    recommendation VARCHAR(50),
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (manuscript_id) REFERENCES manuscripts(id),
    FOREIGN KEY (reviewer_id) REFERENCES users(id)
)";
if (!$link->query($create_table_sql)) {
    die("Error creating table: " . $link->error);
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])){
    $manuscript_id = trim($_POST['manuscript_id']);
    $comments_for_editor = trim($_POST['comments_for_editor']);
    $comments_for_author = trim($_POST['comments_for_author']);
    $recommendation = trim($_POST['recommendation']);

    $sql = "INSERT INTO reviews (manuscript_id, reviewer_id, comments_for_editor, comments_for_author, recommendation) VALUES (?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "iisss", $manuscript_id, $_SESSION['id'], $comments_for_editor, $comments_for_author, $recommendation);
        if(!mysqli_stmt_execute($stmt)){
            echo "Something went wrong. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Get all manuscripts assigned to the current reviewer
$sql = "SELECT m.id, m.title, m.abstract FROM manuscripts m JOIN manuscript_reviewers mr ON m.id = mr.manuscript_id WHERE mr.reviewer_id = ?";
$manuscripts = [];
if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        while($row = mysqli_fetch_assoc($result)){
            $manuscripts[] = $row;
        }
        mysqli_free_result($result);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reviewer Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Journal Management System - Reviewer Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Manuscripts Assigned to You for Review</h2>
        <?php foreach($manuscripts as $manuscript): ?>
            <div class="manuscript-review-box">
                <h3><?php echo htmlspecialchars($manuscript['title']); ?></h3>
                <p><strong>Abstract:</strong> <?php echo htmlspecialchars($manuscript['abstract']); ?></p>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="manuscript_id" value="<?php echo $manuscript['id']; ?>">
                    <div class="form-group">
                        <label>Comments for the Editor</label>
                        <textarea name="comments_for_editor" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Comments for the Author</label>
                        <textarea name="comments_for_author" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Recommendation</label>
                        <select name="recommendation" class="form-control">
                            <option value="Accept">Accept</option>
                            <option value="Minor Revisions">Minor Revisions</option>
                            <option value="Major Revisions">Major Revisions</option>
                            <option value="Reject">Reject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit_review" class="btn btn-primary" value="Submit Review">
                    </div>
                </form>
            </div>
        <?php endforeach; ?>
    </main>

    <footer>
        <p>&copy; 2025 Journal Management System</p>
    </footer>
</body>
</html>
