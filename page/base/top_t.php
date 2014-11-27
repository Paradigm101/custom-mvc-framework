<!DOCTYPE html>
<html>
    <head>
        <!-- metas and title -->
        <meta charset="utf-8">
        <meta name="description" content="My first website using a custom made PHP MVC framework and Bootstrap 3">
        <title><?=  $data['title'] ?></title>

        <!-- CSS files -->
<?      foreach( $data['css_files'] as $cssFile ) { ?>
        <link type="text/css" rel="stylesheet" href="<?= $cssFile ?>" />
<?      } ?>

    </head>
    <body>

        <!-- Start of body -->
        <div class="container">
