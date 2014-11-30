
        <!-- End of body -->
        </div><!-- End of container -->

        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="page/base/jquery/jquery.js"></script>

        <!-- Transfer from PHP to Javascript -->
        <script type="text/javascript">

            // Get javascript from back-end
            <?= Page_LIB::getJavascript() ?>
        </script>

        <!-- Javascript files -->
<?      foreach( $data['script_files'] as $scriptFile ) { ?>
        <script type="text/javascript" src="<?= $scriptFile ?>"></script>
<?      } ?>

    </body>
</html>
