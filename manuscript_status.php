<?php
// ... (php code remains the same)
require_once "php/header.php";
?>
<main>
    <h2>Your Submitted Manuscripts</h2>
    <div class="table-responsive">
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
    </div>
</main>
<?php require_once "php/footer.php"; ?>
