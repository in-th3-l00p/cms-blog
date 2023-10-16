<?php 
    session_start(); 
    require("utils/database.php");
?>
<?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = sanitizeInput($_POST["username"]); 
        $password = sanitizeInput($_POST["password"]); 

        try {
            $passwordQuery = $conn->database->query(
                "SELECT id, password, admin FROM users WHERE username=\"" . $username . "\""
            );

            if ($passwordQuery->num_rows === 0)
                throw new Exception();

            $assoc = $passwordQuery->fetch_assoc();
            if (!password_verify($password, $assoc["password"]))
                throw new Exception();
            
            $_SESSION["id"] = $assoc["id"];
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
    <link rel="stylesheet" href="/css/forms.css">
</head>
<body>
    <?php require("utils/layout/header.php") ?>

    <div class="absolute">
        <section id="form-container">
            <h1 class="title">Login</h1>
            <form method="POST">
                <?php
                    if (isset($error)) 
                        echo "<p class=\"error\">" . $error . "</p>";
                ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" class="input">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="input">
                </div>
                <button type="submit" class="btn mx-auto">Login</button>
            </form>
        </section>

        <?php require("utils/layout/footer.php") ?>
    </div>
</body>
</html>