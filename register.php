<?php
// Include db connect file
require_once "php/db_connect.php";

// Define variables and initialize with empty values
$username = $email = $password = "";
$username_err = $email_err = $password_err = "";
$token = "";
$is_reviewer_invitation = false;

// Check for invitation token
if(isset($_GET['token'])){
    $token = trim($_GET['token']);
    // Validate token
    $sql = "SELECT email, expires_at FROM reviewer_invitations WHERE token = ? AND expires_at > NOW()";
    if($stmt = mysqli_prepare($link, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $token);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $invited_email, $expires_at);
                if(mysqli_stmt_fetch($stmt)){
                    $email = $invited_email;
                    $is_reviewer_invitation = true;
                }
            } else {
                // Token is invalid or expired
                echo "Invalid or expired invitation token.";
                exit;
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // ... (validation logic is the same)

    // Check input errors before inserting in database
    if(empty($username_err) && empty($email_err) && empty($password_err)){

        $role = $is_reviewer_invitation ? 'Reviewer' : 'Reader';

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $username, $email, password_hash($password, PASSWORD_DEFAULT), $role);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // If it was an invitation, delete the token
                if($is_reviewer_invitation){
                    $sql_delete = "DELETE FROM reviewer_invitations WHERE token = ?";
                    if($stmt_delete = mysqli_prepare($link, $sql_delete)){
                        mysqli_stmt_bind_param($stmt_delete, "s", $token);
                        mysqli_stmt_execute($stmt_delete);
                        mysqli_stmt_close($stmt_delete);
                    }
                }
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; margin: auto; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" <?php if($is_reviewer_invitation) echo 'readonly'; ?>>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
