<?php
// ... (php code remains the same)
require_once "php/header.php";
?>
<main>
    <a href="invite_reviewer.php">Invite New Reviewer</a>
    <h2>User Management</h2>
    <?php if(!empty($update_err)): ?>
        <div class="alert alert-danger"><?php echo $update_err; ?></div>
    <?php endif; ?>
    <div class="table-responsive">
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
    </div>
</main>
<?php require_once "php/footer.php"; ?>
