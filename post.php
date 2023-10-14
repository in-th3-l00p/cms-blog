<?php session_start(); ?>
<?php 
    try {
        require("utils/database.php");
        $post = $conn->database->query(
            "SELECT * FROM posts WHERE id=" . $_GET["postId"]
        )->fetch_assoc();
        if (
            $post["visibility"] === "private" && 
            isset($_SESSION["admin"]) && 
            !$_SESSION["admin"]
        )
            throw new Exception();

        $user = $conn->database->query(
            "SELECT * FROM users WHERE id=" . $post["user_id"]
        )->fetch_assoc();
    } catch (Exception $exception) {
        header("Location: /");
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset='utf-8'></meta>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <title>CMS Blog System</title>

        <link rel="stylesheet" href="/css/globals.css">
        <script src="/js/markdown.js"></script>
    </head>

    <body>
        <?php require("utils/layout/header.php") ?>

        <section id="details">
            <h2 id="title"><?php echo $post["title"] ?></h2>
            <p id="description"><?php echo $post["description"] ?></p>
            <span id="less-important">
                <p id="created-by">Created by: <?php echo $user["username"] ?></p>
                <p id="created-at"><?php echo $post["created_at"] ?></p>
            </span>
            <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"]): ?>
                <a href="/edit.php?postId=<?php echo $post["id"] ?>">Edit</a>
            <?php endif ?>
        </section>

        <section id="content"></section>

        <?php require("utils/layout/footer.php") ?>
        <script>
            document.getElementById("content").innerHTML = 
                parseMarkdown(`<?php echo $post["content"] ?>`);
        </script>
    </body>
</html>