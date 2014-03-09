
        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="includes/js/commun/jquery-2.1.0.js"></script>

        <!-- Javascript for every page -->
        <script type="text/javascript" src="includes/js/base.js"></script>

        <!-- Javascript(s) for this page -->
<?      foreach( $data['templates'] as $template ) { ?>
        <script type="text/javascript" src="includes/js/<?= $template ?>.js"></script>
<?      } ?>

        <!-- Javascript for header/footer -->
<?      if ( $data[ 'header' ] ) { ?>
        <script type="text/javascript" src="includes/js/commun/<?= $data[ 'header' ] ?>.js"></script>
<?      } ?>
<?      if ( $data[ 'footer' ] ) { ?>
        <script type="text/javascript" src="includes/js/commun/<?= $data[ 'footer' ] ?>.js"></script>
<?      } ?>

        <!-- BOOTSTRAP -->
        <!-- Latest compiled and minified JavaScript -->
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    </body>
</html>