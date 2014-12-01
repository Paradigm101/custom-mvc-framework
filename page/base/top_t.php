<!DOCTYPE html>
<html>
    <head>
        <!-- Metadata -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS files -->
<?php   foreach( $data['css_files'] as $cssFile ) { ?>
        <link type="text/css" rel="stylesheet" href="<?= $cssFile ?>" />
<?php   } ?>

    </head>
    <body>

        <!-- Start of body -->
        <div class="container" style="margin-bottom: 70px">
