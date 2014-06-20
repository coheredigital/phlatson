<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page->title ?> | Admin</title>
    <?php foreach ($config->styles as $file) {
        echo "<link rel='stylesheet' href='{$file}' type='text/css'>";
    } ?>
    <?php foreach ($config->scripts as $file) {
        echo "<script src='{$file}'></script>";
    } ?>
</head>