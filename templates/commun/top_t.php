<!DOCTYPE html>
<html>
    <head>
        <!-- metas and title -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS for all pages -->
        <link type="text/css" rel="stylesheet" href="includes/css/base.css" />

        <!-- CSS for this page -->
<?      foreach( $data['templates'] as $template ) { ?>
        <link type="text/css" rel="stylesheet" href="includes/css/<?= $template ?>.css" />
<?      } ?>

        <!-- CSS for header/footer -->
<?      if ( $data[ 'header' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="includes/css/commun/<?= $data[ 'header' ] ?>.css" />
<?      } ?>
<?      if ( $data[ 'footer' ] ) { ?>
            <link type="text/css" rel="stylesheet" href="includes/css/commun/<?= $data[ 'footer' ] ?>.css" />
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- main CSS -->
        <link type="text/css" rel="stylesheet" href="includes/css/commun/bootstrap.css">
        <!-- Optional theme -->
        <link type="text/css" rel="stylesheet" href="includes/css/commun/bootstrap-theme.css">
        <!-- end BOOTSTRAP -->
    </head>
    <body>
