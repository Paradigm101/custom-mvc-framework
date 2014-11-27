<!DOCTYPE html>
<html>
    <head>
        <!-- metas and title -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS for all pages -->
        <link type="text/css" rel="stylesheet" href="page/base/base.css" />

        <!-- CSS for this page (if exists) AND header/footer -->
<?      foreach( $data['css_files'] as $cssFile ) { ?>
        <link type="text/css" rel="stylesheet" href="<?= $cssFile ?>" />
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- main CSS -->
        <link type="text/css" rel="stylesheet" href="page/bootstrap/css/bootstrap.css">
        <!-- Optional theme -->
        <link type="text/css" rel="stylesheet" href="page/bootstrap/css/bootstrap-theme.css">
        <!-- end BOOTSTRAP -->
    </head>
    <body>

        <!-- Start of body -->
        <div class="container">
