<?php
// Initialize the session
session_start();

// Check if the user is logged in and is an Author
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== "Author"){
    header("location: login.php");
    exit;
}

// Include db connect file
require_once "php/db_connect.php";

// Get all manuscripts for the current author
$sql = "SELECT title, submitted_at, status FROM manuscripts WHERE author_id = ? ORDER BY submitted_at DESC";
$manuscripts = [];
if($stmt = mysqli_prepare($link, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                $manuscripts[] = $row;
            }
            mysqli_free_result($result);
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    mysqli_stmt_close($stmt);
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manuscript Status</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Journal Management System - Manuscript Status</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="submit_manuscript.php">Submit Manuscript</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <h2>Your Submitted Manuscripts</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($manuscripts)): ?>
                    <tr>
                        <td colspan="3">You have not submitted any manuscripts yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($manuscripts as $manuscript): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($manuscript['title']); ?></td>
                        <td><?php echo $manuscript['submitted_at']; ?></td>
                        <td><?php echo htmlspecialchars($manuscript['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Journal Management System</p>
    </footer>
</body>
</html>
