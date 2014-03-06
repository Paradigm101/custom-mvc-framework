<!DOCTYPE html>
<html>
    <head>
        <!-- metas and title -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS for all pages -->
        <link type="text/css" rel="stylesheet" href="views/css/base.css" />

        <!-- CSS for the specific page -->
        <link type="text/css" rel="stylesheet" href="views/css/<?= $data['page'] ?>.css" />

        <!-- CSS for header/footer -->
<?      if ( $data[ 'header' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="views/css/commun/<?= $data[ 'header' ] ?>.css" />
<?      } ?>
<?      if ( $data[ 'footer' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="views/css/commun/<?= $data[ 'footer' ] ?>.css" />
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
        <!-- Optional theme -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
        <!-- BOOTSTRAP -->
    </head>
    <body>
