<!DOCTYPE html>
<html>
    <head>
        <!-- CSS for every screen -->
        <link type="text/css" rel="stylesheet" href="views/css/base.css" />
        
        <!-- CSS for this screen -->
        <link type="text/css" rel="stylesheet" href="views/css/<?= $data['page'] ?>.css" />

        <!-- CSS for header/footer -->
<?php if ( $data[ 'header' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="views/css/commun/<?= $data[ 'header' ] ?>.css" />
<?php } ?>
<?php if ( $data[ 'footer' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="views/css/commun/<?= $data[ 'footer' ] ?>.css" />
<?php } ?>

        <!-- BOOTSTRAP -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
        <!-- BOOTSTRAP -->

        <title><?=  $data['title'] ?></title>
    </head>
    <body>
