
        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="page/jquery/script/jquery.js"></script>

        <!-- Transfer from PHP to Javascript -->
        <script type="text/javascript">

// Get javascript from back-end
//-----------------------------

<?= Session_Manager_LIB::getJavascript() ?>

        </script>

        <!-- Javascript for every page -->
        <script type="text/javascript" src="page/base/script/base.js"></script>

        <!-- Javascript(s) for this specific page -->
<?      foreach( $data['templates'] as $template ) { ?>
            <script type="text/javascript" src="page/<?= $data['page'] ?>/script/<?= $template ?>.js"></script>
<?      } ?>

        <!-- Javascript for header -->
<?      if ( $data[ 'header' ] ) { ?>
            <script type="text/javascript" src="page/<?= $data[ 'header' ] ?>/script/<?= $data[ 'header' ] ?>.js"></script>
<?      } ?>

        <!-- Javascript for footer -->
<?      if ( $data[ 'footer' ] ) { ?>
            <script type="text/javascript" src="page/<?= $data[ 'footer' ] ?>/script/<?= $data[ 'footer' ] ?>.js"></script>
<?      } ?>

        <!-- BOOTSTRAP -->
        <script type="text/javascript" src="page/bootstrap/script/bootstrap.js"></script>
    </body>
</html>
