<?php 
    session_start(); 
    require("utils/database.php");
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
        header("Location: /");
        die();
    }
?>
<?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = sanitizeInput($_POST["title"]);
        $description = sanitizeInput($_POST["description"]);

        try {
            $count = $conn->database->query(
                "SELECT COUNT(*) as count FROM posts WHERE title=\"" . $title . "\""
            )->fetch_assoc()["count"];
            if ((int)$count > 0)
                throw new Exception("Title is already used");
            if (!$conn->database->query(
                    "INSERT posts (title, description, content, user_id) VALUES " .
                    "(\"" . $title . "\", \"" . $description . "\", \"\", \"" . $_SESSION["id"] . "\")"
            ))
                throw new Exception("Database error");
            $id = $conn->database->query(
                "SELECT id FROM posts WHERE title=\"" . $title . "\""
            )->fetch_row()[0];
            header("Location: /edit.php?postId=" . $id);
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

    <form method="POST">
        <?php
            if (isset($error)) 
                echo "<p class=\"error\">" . $error . "</p>";
        ?>
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <button type="submit" class="btn">Create</button>
    </form>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>