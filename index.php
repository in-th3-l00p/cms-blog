<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS blog system</title>
</head>
<body>
    <header>
        <h1>Blog</h1>

        <span id="auth-links">
            <?php
                // implement ssr for showing login/register links or account information  

                echo "<a href=\"/login.php\">Login</a>";
                echo "<a href=\"/register.php\">Register</a>";
            ?>
        </span>
    </header>

    <!--Presentation section-->
    <section id="presentation">
        <h2>CMS system built from scratch</h2>
        <h3>With the purpose of getting familiar with the basics of web development</h3>
    </section>

    <section id="posts">
        <ul>
            <?php 
                // show posts 
            ?>
        </ul>
    </section>

    <footer>
        <p>All rights reserved</p>
        <ul id="links-list">
            <li><a href="https://intheloop.bio">website</a></li>
            <li><a href="https://github.com/in-th3-l00p">github</a></li>
            <li><a href="">linkedin</a></li>
        </ul>
    </footer>
</body>
</html>