<?php 
    session_start(); 
    require("utils/database.php");
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
        header("Location: /");
        die();
    }
?>
<?php 
    if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
        header("Location: /");
        die();
    }

    try {
        $post = $conn->database->query(
            "SELECT * FROM posts WHERE id=" . sanitizeInput($_GET["postId"])
        )->fetch_assoc();

        $user = $conn->database->query(
            "SELECT * FROM users WHERE id=" . sanitizeInput($post["user_id"])
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
                    "UPDATE posts SET content=\"" . sanitizeInput($_POST["content"]) . "\" " .
                    "WHERE id=\"" . $post["id"] . "\""
                ))
                    throw new Exception("failed to update");
            } else {
                if (!$conn->database->query(
                    "UPDATE posts SET " . 
                    "title=\"" . sanitizeInput($_POST["title"]) . 
                    "\", description=\"" . sanitizeInput($_POST["description"]) . "\", " .
                    "visibility=\"" . sanitizeInput($_POST["visibility"]) . "\" " .
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
    <link rel="stylesheet" href="/css/forms.css">
    <link rel="stylesheet" href="/css/edit.css">
</headu>
<body>
    <?php require("utils/layout/header.php") ?>

    <?php
        if (isset($error)) 
            echo "<p class=\"error\">" . $error . "</p>";
    ?>

    <form method="POST" id="details-form">
        <div class="form-group">
            <label for="title">Title</label>
            <input 
                type="text" 
                name="title" 
                id="title" 
                value="<?php echo $post["title"]?>"
                class="input"
            >
        </div>
        <div class="textarea-form-group">
            <label for="description">Description:</label>
            <textarea 
                name="description" 
                id="description"
                class="input"
            ><?php echo $post["description"]?></textarea>
        </div>
        <div class="form-group">
            <label for="visibility">Visiblity</label>
            <select name="visibility" id="visiblity" class="input">
                <?php if ($post["visibility"] === "public"): ?>
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                <?php else: ?>
                    <option value="private">Private</option>
                    <option value="public">Public</option>
                <?php endif ?>
            </select>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn">Update</button>
            <a href="/post.php?postId=<?php echo $post["id"]; ?>">
                <button type="button" class="btn">Preview</button>
            </a>
        </div>
    </form>

    <form method="POST" id="content-form">
        <div class="textarea-form-group">
            <label for="content">Content (in markdown):</label>
            <textarea 
                name="content" 
                id="content"
                class="input"
            ><?php echo $post["content"] ?></textarea>
        </div>
        <button type="submit" class="btn mx-auto">Update content</button>
    </form>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>
