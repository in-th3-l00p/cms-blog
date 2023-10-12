<?php session_start(); ?>
<?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"]; 
        $password = $_POST["password"]; 

        require("utils/database.php");
        try {
            $passwordQuery = $conn->database->query(
                "SELECT password, admin FROM users WHERE username=\"" . $username . "\""
            );

            if ($passwordQuery->num_rows === 0)
                throw new Exception();

            $assoc = $passwordQuery->fetch_assoc();
            if (!password_verify($password, $assoc["password"]))
                throw new Exception();
            
            $_SESSION["username"] = $username;
            $_SESSION["admin"] = $assoc["admin"];
            header("Location: /");
            die();
        } catch (Exception $e) {
            $error = "Invalid username or password";
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
            <button type="submit" class="btn">Register</button>
        </form>
    </section>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>