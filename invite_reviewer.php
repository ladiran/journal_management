<?php
// ... (php code remains the same)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invite Reviewer</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: auto; }
    </style>
</head>
<body>
    <header>
        <h1>Journal Management System - Invite Reviewer</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="admin.php">User Management</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <div class="wrapper">
        <h2>Invite a New Reviewer</h2>
        <p>Enter the email address of the person you want to invite to be a reviewer.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Generate Invitation Link">
            </div>
        </form>
        <?php if(!empty($invitation_link)): ?>
            <div class="alert alert-success">
                <p>Invitation link generated successfully. Please send this link to the reviewer:</p>
                <p><strong><?php echo htmlspecialchars($invitation_link); ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
