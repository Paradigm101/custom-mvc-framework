
        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="includes/js/jquery-1.9.1.js"></script>

        <!-- Javascript for every page -->
        <script type="text/javascript" src="includes/js/base.js"></script>

        <!-- Javascript(s) for this page -->
<?      foreach( $data['templates'] as $template ) { ?>
        <script type="text/javascript" src="includes/js/<?= $template ?>.js"></script>
<?      } ?>

        <!-- Javascript for header/footer -->
<?      if ( $data[ 'header' ] ) { ?>
            <link type="text/javascript" src="includes/js/commun/<?= $data[ 'header' ] ?>.js" />
<?      } ?>
<?      if ( $data[ 'footer' ] ) { ?>
            <link type="text/javascript" src="includes/js/commun/<?= $data[ 'footer' ] ?>.js" />
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </body>
</html>