<?php include "page-head.php" ?>
<body class="<?php echo "page-{$page->name}" ?>">

    <div id="header">
        <div class="container">
            <?php include "main-menu.php" ?>
        </div>
    </div>


    <div id="main">

            <?php echo $output; ?>

    </div>
    <div id="footer">
        <div class="container">

        </div>
    </div>
    <?php foreach ($config->scripts as $file) {
        echo "<script src='{$file}'></script>";
    } ?>
</body>
</html>
