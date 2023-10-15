<header>
    <a href="/">
        <span id="brand">
            <h2>Blog</h2>
        </span>
    </a>

    <span id="auth-links">
        <?php
            if (isset($_SESSION["username"])) {
                if ($_SESSION["admin"])
                    echo "<a href=\"create.php\">Create</a>";
                echo "<a href=\"logout.php\">Logout</a>";
            } else {
                echo "<a href=\"/login.php\">Login</a>";
                echo "<a href=\"/register.php\">Register</a>";
            }
        ?>
    </span>
</header>