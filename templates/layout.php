<!-- templates/layout.php -->
<html>
    <head>
        <title><?php echo $title ?></title>
        <link href="<?php global $base; echo $base ?>web/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <?php echo $content ?>
        </div>
    </body>
</html>