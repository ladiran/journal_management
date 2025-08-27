<?php
// ... (php code remains the same)
require_once "php/header.php";
?>
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
<?php require_once "php/footer.php"; ?>
