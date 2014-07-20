<?php include "includes/page-head.php" ?>
<body class="<?php echo "page-{$page->name}" ?>">

<div id="header">
    <?php include "includes/main-menu.php" ?>
</div>


<div id="main">
    <?php echo $output->main ?>
</div>
<div id="footer">

</div>
<?php foreach ($config->scripts as $file) {
    echo "<script src='{$file}'></script>";
} ?>
</body>
</html>
