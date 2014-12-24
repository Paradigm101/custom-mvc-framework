
        <!-- End of body -->
        </div><!-- End of container -->

        <!-- script in the end to display the page as fast as possible -->
        <!-- JQuery -->
        <script type="text/javascript" src="<?= SITE_ROOT ?>/page/base/jquery/jquery.js"></script>

        <!-- Base for all pages -->
        <script type="text/javascript" src="<?= SITE_ROOT ?>/page/base/base.js"></script>
        
        <!-- Transfer from PHP to Javascript -->
        <script type="text/javascript">

            // Get javascript from back-end
            <?= Page_LIB::getJavascript() ?>
        </script>

        <!-- Javascript files -->
<?php   foreach( $scriptFiles as $scriptFile ) { ?>
        <script type="text/javascript" src="<?= $scriptFile ?>"></script>
<?php   } ?>

    </body>
</html>
