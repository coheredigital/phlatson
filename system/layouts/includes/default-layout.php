<?php include "page-head.php" ?>
<body class="<?php echo "page-{$page->name}" ?>">

    <div id="header">
        <div class="container">
            <?php include "main-menu.php" ?>
        </div>
    </div>


    <div id="main">
        <div class="container">
            <?php echo $output; ?>
        </div>
    </div>
    <div id="footer">
        <div class="container">

        </div>
    </div>

</body>
</html>
