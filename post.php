<?php 
    session_start(); 
    require("utils/database.php");
?>
<?php 
    try {
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
        <link rel="stylesheet" href="/css/post.css">
        <script src="/js/markdown.js"></script>
    </head>

    <body>
        <?php require("utils/layout/header.php") ?>

        <section id="details">
            <span id="main">
                <h1 id="title"><?php echo $post["title"] ?></h1>
                <p id="description"><?php echo $post["description"] ?></p>
            </span>
            <span id="less-important">
                <p id="created-by">Created by: <?php echo $user["username"] ?></p>
                <p id="created-at"><?php echo $post["created_at"] ?></p>
            </span>
        </section>

        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"]): ?>
            <section id="admin">
                <a 
                    href="/edit.php?postId=<?php echo $post["id"] ?>"
                    class="btn square"
                    title="Edit"
                >
                    <img src="/assets/edit.svg" alt="edit">
                </a>
            </section>
        <?php endif ?>

        <section id="content"></section>

        <?php require("utils/layout/footer.php") ?>
        <script>
            document.getElementById("content").innerHTML = 
                parseMarkdown(`<?php echo $post["content"] ?>`);
        </script>
    </body>
</html>