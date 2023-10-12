<header>
    <h1>Blog</h1>

    <span id="auth-links">
        <?php
            if (isset($_SESSION["username"])) {
                echo "<a href=\"logout.php\">Logout</a>";
            } else {
                echo "<a href=\"/login.php\">Login</a>";
                echo "<a href=\"/register.php\">Register</a>";
            }
        ?>
    </span>
</header>