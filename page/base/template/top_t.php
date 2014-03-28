<!DOCTYPE html>
<html>
    <head>
        <!-- metas and title -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS for all pages -->
        <link type="text/css" rel="stylesheet" href="page/base/css/base.css" />

        <!-- CSS for this page -->
<?      foreach( $data['templates'] as $template ) { ?>
        <link type="text/css" rel="stylesheet" href="page/<?= $data['page'] ?>/css/<?= $template ?>.css" />
<?      } ?>

        <!-- CSS for header/footer -->
<?      if ( $data[ 'header' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="page/<?= $data['header'] ?>/css/<?= $data['header'] ?>.css" />
<?      } ?>
<?      if ( $data[ 'footer' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="page/<?= $data['footer'] ?>/css/<?= $data['footer'] ?>.css" />
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- main CSS -->
        <link type="text/css" rel="stylesheet" href="page/bootstrap/css/bootstrap.css">
        <!-- Optional theme -->
        <link type="text/css" rel="stylesheet" href="page/bootstrap/css/bootstrap-theme.css">
        <!-- end BOOTSTRAP -->
    </head>
    <body>
