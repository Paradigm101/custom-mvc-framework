<!DOCTYPE html>
<html>
    <head>
        <!-- Metadata -->
        <meta charset="utf-8">
        <meta name="description" content="Website example using a custom made PHP MVC framework, Bootstrap 3 and JQuery">
        <title><?=  $title ?></title>

        <!-- CSS files -->
<?php   foreach( $cssFiles as $cssFile ) { ?>
        <link type="text/css" rel="stylesheet" href="<?= $cssFile ?>" />
<?php   } ?>

    </head>
    <body style="background-image: url('<?= SITE_ROOT ?>/page/base/image/background.png');">

        <!-- Start of body -->
        <div class="container" style="margin-bottom: 80px;">
