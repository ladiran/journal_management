<?php
// Initialize the session
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Journal Management System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Journal Management System</h1>
        <nav>
            <ul>
                <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                    <li><a href="profile.php">Profile (<?php echo htmlspecialchars($_SESSION["username"]); ?>)</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section id="announcements">
            <h2>Announcements</h2>
            <p>Welcome to the new Journal Management System!</p>
        </section>

        <section id="published-manuscripts">
            <h2>Published Manuscripts</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publication Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>The Impact of AI on Software Development</td>
                        <td>John Doe</td>
                        <td>2025-08-27</td>
                    </tr>
                    <tr>
                        <td>A Study of Quantum Computing</td>
                        <td>Jane Smith</td>
                        <td>2025-08-20</td>
                    </tr>
                    <tr>
                        <td>The Future of Web Development</td>
                        <td>Peter Jones</td>
                        <td>2025-08-15</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>&copy; 2025 Journal Management System</p>
    </footer>
</body>
</html>
