<?php session_start(); ?>
<?php 
    require("utils/database.php");
    const PAGE_SIZE = 10;

    $page = isset($_GET["page"]) ? $_GET["page"] : 0;
    if (isset($_SESSION["admin"]) && $_SESSION["admin"])
        $private_page = isset($_GET["privatePage"]) ? $_GET["privatePage"] : 0;

    try {
        $posts = $conn->database->query(
            "SELECT * FROM posts WHERE visibility=\"public\" LIMIT " . $page * PAGE_SIZE . ", " . PAGE_SIZE
        )->fetch_all(MYSQLI_ASSOC);
        $posts_count = (int)$conn->database->query(
            "SELECT COUNT(*) as count FROM posts"
        )->fetch_assoc()["count"];

        if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            $private_posts = $conn->database->query(
                "SELECT * FROM posts WHERE visibility=\"private\" LIMIT " . $private_page * PAGE_SIZE . ", " . PAGE_SIZE
            )->fetch_all(MYSQLI_ASSOC);
            $private_posts_count = $conn->database->query(
                "SELECT COUNT(*) as count FROM posts WHERE visibility=\"private\""
            )->fetch_assoc()["count"];
        }
    } catch (Exception $e) {
        die("failed to load posts from db: " . $e->getMessage());
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS blog system</title>

    <link rel="stylesheet" href="/css/globals.css">
    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
    <?php require("utils/layout/header.php") ?>

    <!--Presentation section-->
    <section id="presentation">
        <h2>CMS system built from scratch</h2>
        <h3>With the purpose of getting familiar with the basics of web development</h3>
    </section>

    <section id="posts">
        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"]): ?>
            <h2>Private posts</h2>
            <ul id="posts-list">
                <?php foreach ($private_posts as $post): ?>
                    <li><a href="/post.php?postId=<?php echo $post["id"]; ?>">
                        <?php echo $post["title"]; ?>
                    </a></li>
                <?php endforeach ?>
            </ul>
            <ul class="page-buttons">
                <?php for ($p = 0; $p < ceil($private_posts_count / PAGE_SIZE); $p++): ?>
                    <li><a href="/?privatePage=<?php echo $p; ?>">
                        <button 
                            <?php if ((int)$page === $p) echo "disabled"; ?> 
                            class="page-btn"
                        >
                            <?php echo $p; ?>
                        </button>
                    </a></li>
                <?php endfor ?>
            </ul>
        <?php endif ?>

        <h2>Posts</h2>
        <ul id="posts-list">
            <?php foreach ($posts as $post): ?>
                <li><a href="/post.php?postId=<?php echo $post["id"]; ?>">
                    <?php echo $post["title"]; ?>
                </a></li>
            <?php endforeach ?>
        </ul>
        <ul class="page-buttons">
            <?php for ($p = 0; $p < ceil($posts_count / PAGE_SIZE); $p++): ?>
                <li><a href="/?page=<?php echo $p; ?>">
                    <button 
                        <?php if ((int)$page === $p) echo "disabled"; ?> 
                        class="page-btn"
                    >
                        <?php echo $p; ?>
                    </button>
                </a></li>
            <?php endfor ?>
        </ul>
    </section>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>