<?php
// ... (session start and role check remains the same)

// Include db connect file
require_once "php/db_connect.php";

// ... (table creation and form processing for assignments remains the same)

// Logic for EIC making a final decision
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['make_decision']) && $_SESSION["role"] === "Editor in Chief"){
    $manuscript_id = trim($_POST['manuscript_id']);
    $decision = trim($_POST['decision']);

    if(in_array($decision, ['Accepted', 'Rejected'])){
        $sql = "UPDATE manuscripts SET status = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $decision, $manuscript_id);
            if(!mysqli_stmt_execute($stmt)){
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}


// Get all manuscripts and their reviews
$sql = "SELECT m.id, m.title, m.status, r.comments_for_editor, r.recommendation, u_rev.username as reviewer_name FROM manuscripts m LEFT JOIN reviews r ON m.id = r.manuscript_id LEFT JOIN users u_rev ON r.reviewer_id = u_rev.id";
// ... (filtering for Section Editor remains the same)
$sql .= " ORDER BY m.submitted_at DESC, r.submitted_at ASC";

$manuscripts_with_reviews = [];
if($result = mysqli_query($link, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $manuscript_id = $row['id'];
        if(!isset($manuscripts_with_reviews[$manuscript_id])){
            $manuscripts_with_reviews[$manuscript_id] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'status' => $row['status'],
                'reviews' => []
            ];
        }
        if(!empty($row['reviewer_name'])){
            $manuscripts_with_reviews[$manuscript_id]['reviews'][] = [
                'reviewer_name' => $row['reviewer_name'],
                'comments' => $row['comments_for_editor'],
                'recommendation' => $row['recommendation']
            ];
        }
    }
    mysqli_free_result($result);
}

// ... (rest of the PHP logic)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- ... (header remains the same) -->
    <main>
        <!-- ... (manuscript table) -->
        <?php foreach($manuscripts_with_reviews as $manuscript): ?>
        <div class="manuscript-details">
            <h4>Reviews for "<?php echo htmlspecialchars($manuscript['title']); ?>"</h4>
            <?php if(empty($manuscript['reviews'])): ?>
                <p>No reviews submitted yet.</p>
            <?php else: ?>
                <ul>
                <?php foreach($manuscript['reviews'] as $review): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($review['reviewer_name']); ?>:</strong>
                        <?php echo htmlspecialchars($review['recommendation']); ?> -
                        <em><?php echo htmlspecialchars($review['comments']); ?></em>
                    </li>
                <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if($_SESSION["role"] === "Editor in Chief" && $manuscript['status'] === 'Under Review'): ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="margin-top: 10px;">
                    <input type="hidden" name="manuscript_id" value="<?php echo $manuscript['id']; ?>">
                    <select name="decision">
                        <option value="Accepted">Accept</option>
                        <option value="Rejected">Reject</option>
                    </select>
                    <input type="submit" name="make_decision" value="Make Final Decision">
                </form>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </main>
    <!-- ... (footer remains the same) -->
</body>
</html>
