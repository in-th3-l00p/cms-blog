<?php 
    session_start(); 
    require("utils/database.php");
?>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = sanitizeInput(trim($_POST["username"]));
        $password = sanitizeInput(trim($_POST["password"]));
        try {
            if ($password !== sanitizeInput($_POST["cpassword"]))    
                throw new Exception("Passwords do not match");
            if (strlen($password) < 8)
                throw new Exception("Password should have at least 8 characters");
            if (strlen($username) < 1)
                throw new Exception("Invalid username");
            $query = $conn->database->query("SELECT * FROM users WHERE username=\"" . $username . "\"");
            if ($query->num_rows > 0)
                throw new Exception("Username is already used");

            $password = password_hash($password, 0);
            if (!$conn->database->query(
                "INSERT INTO users (username, password) VALUES " .
                "(\"" . $username . "\", \"" . $password . "\")"
            ))
                throw new Exception("Database error");
            
            header("Location: " . "/login.php");
            die();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS blog system</title>

    <link rel="stylesheet" href="/css/globals.css">
</head>
<body>
    <?php require("utils/layout/header.php") ?>

    <section id="form-container">
        <form method="POST">
            <?php
                if (isset($error)) 
                    echo "<p class=\"error\">" . $error . "</p>";
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

    <?php require("utils/layout/footer.php") ?>
</body>
</html>
