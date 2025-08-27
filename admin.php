<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check if the user is an admin or editor in chief
if($_SESSION["role"] !== "Admin" && $_SESSION["role"] !== "Editor in Chief"){
    header("location: index.php");
    exit;
}

// Include db connect file
require_once "php/db_connect.php";

// Define variables and initialize with empty values
$new_role = $user_id_to_update = "";
$update_err = "";

// Available roles
$roles = ["Reader", "Author", "Editor in Chief", "Assistant Editor in Chief", "Section Editor", "Reviewer", "Admin"];

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['update_role'])){
        $user_id_to_update = trim($_POST['user_id']);
        $new_role = trim($_POST['new_role']);

        if(!in_array($new_role, $roles)){
            $update_err = "Invalid role selected.";
        }

        if(empty($update_err)){
            $sql = "UPDATE users SET role = ? WHERE id = ?";
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "si", $new_role, $user_id_to_update);
                if(!mysqli_stmt_execute($stmt)){
                    echo "Something went wrong. Please try again later.";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
}

// Get all users from the database
$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY id";
$users = [];
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $users[] = $row;
        }
        mysqli_free_result($result);
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Journal Management System - User Management</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="profile.php">Profile (<?php echo htmlspecialchars($_SESSION["username"]); ?>)</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <a href="invite_reviewer.php">Invite New Reviewer</a>
        <h2>User Management</h2>
        <?php if(!empty($update_err)): ?>
            <div class="alert alert-danger"><?php echo $update_err; ?></div>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <select name="new_role">
                                <?php foreach($roles as $role): ?>
                                    <option value="<?php echo $role; ?>" <?php if($user['role'] == $role) echo 'selected'; ?>>
                                        <?php echo $role; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="submit" name="update_role" value="Update">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2025 Journal Management System</p>
    </footer>
</body>
</html>
