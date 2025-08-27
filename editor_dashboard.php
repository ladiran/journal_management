<?php
// ... (php code remains the same)
require_once "php/header.php";
?>
<main>
    <h2>
        <?php
        if($_SESSION["role"] === "Section Editor") {
            echo "Your Assigned Manuscripts";
        } else {
            echo "All Submitted Manuscripts";
        }
        ?>
    </h2>
    <div class="table-responsive">
        <table>
            <!-- ... (table content) ... -->
        </table>
    </div>

    <?php foreach($manuscripts_with_reviews as $manuscript): ?>
    <div class="manuscript-details">
        <!-- ... (review details) ... -->
    </div>
    <?php endforeach; ?>
</main>
<?php require_once "php/footer.php"; ?>
