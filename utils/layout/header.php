<header>
    <h2>Blog</h2>

    <span id="auth-links">
        <?php
            if (isset($_SESSION["username"])) {
                echo "<a href=\"logout.php\">Logout</a>";
                if ($_SESSION["admin"])
                    echo "<a href=\"create.php\">Create</a>";
            } else {
                echo "<a href=\"/login.php\">Login</a>";
                echo "<a href=\"/register.php\">Register</a>";
            }
        ?>
    </span>
</header>