<?php 
    session_start(); 
    require("utils/database.php");
?>
<?php 
    const PAGE_SIZE = 10;

    $page = isset($_GET["page"]) ? $_GET["page"] : 0;
    if (isset($_SESSION["admin"]) && $_SESSION["admin"])
        $private_page = isset($_GET["privatePage"]) ? $_GET["privatePage"] : 0;

    try {
        $posts = $conn->database->query(
            "SELECT * FROM posts WHERE visibility=\"public\" " .
            "ORDER BY created_at DESC " .
            "LIMIT " . $page * PAGE_SIZE . ", " . PAGE_SIZE
        )->fetch_all(MYSQLI_ASSOC);
        $posts_count = (int)$conn->database->query(
            "SELECT COUNT(*) as count FROM posts WHERE visibility=\"public\""
        )->fetch_assoc()["count"];

        if (isset($_SESSION["admin"]) && $_SESSION["admin"]) {
            $private_posts = $conn->database->query(
                "SELECT * FROM posts WHERE visibility=\"private\" " .
                "ORDER BY created_at DESC " .
                "LIMIT " . $private_page * PAGE_SIZE . ", " . PAGE_SIZE
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

    <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"]): ?>
        <script defer src="/js/indexAdmin.js"></script>
    <?php endif ?>
</head>
<body>
    <?php require("utils/layout/header.php") ?>

    <!--Presentation section-->
    <section id="presentation">
        <img src="/assets/blog-writing.svg" alt="blog writing" id="icon">
        <span id="text">
            <h2>CMS system built from scratch</h2>
            <h3>With the purpose of getting familiar with the basics of web development</h3>
        </span>
    </section>

    <!-- Posts list -->
    <section id="posts">
        <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"]): ?>
            <span id="private-posts-list-title-container">
                <h2 class="posts-list-title">Private posts:</h2>
                <button id="private-posts-toggler">
                    <img 
                        src="/assets/bottom-arrow.svg" 
                        alt="bottom arrow"
                    >
                </button>
            </span>

            <div id="private-posts-container">
                <?php if ($private_posts_count > 0): ?>
                    <ul class="posts-list">
                        <?php foreach ($private_posts as $post): ?>
                            <li class="post">
                                <a href="/post.php?postId=<?php echo $post["id"]; ?>">
                                    <h2><?php echo $post["title"]; ?></h2>
                                    <p class="date">created at: <span><?php echo $post["created_at"] ?></span></p>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>

                    <ul class="page-buttons">
                        <?php for ($p = 0; $p < ceil($private_posts_count / PAGE_SIZE); $p++): ?>
                            <li>
                                <a href="/?privatePage=<?php echo $p; ?>">
                                <button 
                                    <?php if ((int)$page === $p) echo "disabled"; ?> 
                                    class="btn page-btn"
                                >
                                    <?php echo $p; ?>
                                </button>
                                </a>
                            </li>
                        <?php endfor ?>
                    </ul>
                <?php else: ?>
                    <p class="nothing">There are no private posts</p>
                <?php endif ?>
            </div>
        <?php endif ?>

        <h2 class="posts-list-title">Posts:</h2>
        <?php if ($posts_count > 0): ?>
            <ul class="posts-list">
                <?php foreach ($posts as $post): ?>
                    <li class="post">
                        <a href="/post.php?postId=<?php echo $post["id"]; ?>">
                            <h2><?php echo $post["title"]; ?></h2>
                            <p class="date">created at: <span><?php echo $post["created_at"] ?></span></p>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>
            <ul class="page-buttons">
                <?php for ($p = 0; $p < ceil($posts_count / PAGE_SIZE); $p++): ?>
                    <li><a href="/?page=<?php echo $p; ?>">
                        <button 
                            <?php if ((int)$page === $p) echo "disabled"; ?> 
                            class="btn page-btn"
                        >
                            <?php echo $p; ?>
                        </button>
                    </a></li>
                <?php endfor ?>
            </ul>
        <?php else: ?>
            <p class="nothing">There are no posts</p>
        <?php endif ?>
    </section>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>