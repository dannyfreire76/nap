<?php
include "../includes/common.php";

registerHtmlForm(__LINE__);

?><html lang="EN">
    <head>
        <title>Test auto-post vars</title>
        <script type="text/javascript" src="../includes/jquery.js"></script>
        <script type="text/javascript" src="../includes/_.jquery.js"></script>
    </head>
    <body>
        <form method="post" action="#">
            <fieldset>
                <legend>Who are you?</legend>
                <input type="text" name="first_name" />
                <input type="text" name="last_name" />
                <input type="submit" name="submit" value="OK" />
            </fieldset>
        </form>
        <?php
            print_d($GLOBALS,false);
        ?>
    </body>
</html>
