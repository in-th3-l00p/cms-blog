<?php session_start(); ?>
<?php 
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
        header("Location: /");
        die();
    }

    try {
        require("utils/database.php");
        $post = $conn->database->query(
            "SELECT * FROM posts WHERE id=" . $_GET["postId"]
        )->fetch_assoc();

        $user = $conn->database->query(
            "SELECT * FROM users WHERE id=" . $post["user_id"]
        )->fetch_assoc();
    } catch (Exception $exception) {
        header("Location: /");
        die();
    }
?>
<?php 
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        try {
            if (isset($_POST["content"])) {
                if (!$conn->database->query(
                    "UPDATE posts SET content=\"" . $_POST["content"] . "\" " .
                    "WHERE id=\"" . $post["id"] . "\""
                ))
                    throw new Exception("failed to update");
            } else {
                if (!$conn->database->query(
                    "UPDATE posts SET " . 
                    "title=\"" . $_POST["title"] . "\", description=\"" . $_POST["description"] . "\" " .
                    "WHERE id=" . $post["id"]
                ))
                    throw new Exception("failed to update");
            }

            header("Refresh: 0");
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

    <?php
        if (isset($error)) 
            echo "<p class=\"error\">" . $error . "</p>";
    ?>
    <form method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?php echo $post["title"]?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description"><?php echo $post["description"]?></textarea>
        </div>
        <button type="submit" class="btn">Update</button>
    </form>

    <form method="POST">
        <textarea name="content" id="content"><?php echo $post["content"] ?></textarea>
        <button type="submit">Update content</button>
    </form>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>
