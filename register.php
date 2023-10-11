<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS blog system</title>
</head>
<body>
    <header>
        <h1>Blog</h1>
        <span id="auth-links">
            <?php
                // implement ssr for showing login/register links or account information  

                echo "<a href=\"/login.php\">Login</a>";
                echo "<a href=\"/register.php\">Register</a>";
            ?>
        </span>
    </header>

    <section id="form-container">
        <form method="POST">
            <?php
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $username = trim($_POST["username"]);
                    $password = trim($_POST["password"]);
                    require("utils/database.php");
                    try {
                        if ($password !== trim($_POST["cpassword"]))    
                            throw new Exception("Passwords do not match");
                        if (strlen($password) < 8)
                            throw new Exception("Password should have at least 8 characters");
                        if (strlen($username) < 1)
                            throw new Exception("Invalid username");
                        $query = $conn->database->query("SELECT * FROM users WHERE username=" . $uesrname);
                        if ($query->num_rows > 0)
                            throw new Exception("Username is already used");

                        $password = password_hash($password, 0);
                        if (!$conn->database->query(
                            "INSERT INTO users (username, password) WHERE " .
                            "(" . $username . "," . $password . ")"
                        ))
                            throw new Exception("Database error");
                    } catch (Exception $e) {
                        echo "<p class=\"error\">" . $e->getMessage() . "</p>";
                    }
                }
            ?>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </div>
            <div class="form-group">
                <label for="cpassword">Confirm password</label>
                <input type="password" name="cpassword" id="cpassword">
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </section>

    <footer>
        <p>All rights reserved</p>
        <ul id="links-list">
            <li><a href="https://intheloop.bio">website</a></li>
            <li><a href="https://github.com/in-th3-l00p">github</a></li>
            <li><a href="">linkedin</a></li>
        </ul>
    </footer>
</body>
</html>
