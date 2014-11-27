
        <!-- End of body -->
        </div><!-- End of container -->

        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="page/jquery/jquery.js"></script>

        <!-- Transfer from PHP to Javascript -->
        <script type="text/javascript">

            // Get javascript from back-end
            <?= Page_LIB::getJavascriptForPage() ?>
        </script>

        <!-- Javascript for every page -->
        <script type="text/javascript" src="page/base/base.js"></script>

        <!-- Javascript(s) for this specific page (if needed) and header/footer -->
<?      foreach( $data['script_files'] as $scriptFile ) { ?>
        <script type="text/javascript" src="<?= $scriptFile ?>"></script>
<?      } ?>

        <!-- BOOTSTRAP -->
        <script type="text/javascript" src="page/bootstrap/bootstrap.js"></script>
    </body>
</html>
