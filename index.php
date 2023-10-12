<?php session_start(); ?>
<?php 
    require("utils/database.php");
    $page = isset($_GET["page"]) ? $_GET["page"] : 0;
    const PAGE_SIZE = 10;

    try {
        $query = $conn->database->query(
            "SELECT * FROM posts LIMIT " . $page * PAGE_SIZE . ", " . PAGE_SIZE
        );
        $posts = $query->fetch_all(); 
    } catch (Exception $e) {
        die("failed to load posts from db");
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
        <ul>
            <?php 
                foreach ($posts as $post) {
                    echo "<li><a href=\"/post.php?postId=" . $post[0] . "\">" . 
                            $post[2] . 
                        "</a></li>";
                }
            ?>
        </ul>
    </section>

    <?php require("utils/layout/footer.php") ?>
</body>
</html>