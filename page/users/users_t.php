
<!-- Role OK modal -->
<div class="modal fade" id="roleOkModal">
    <div class="modal-dialog modal-dialog-center">
        <div class="modal-content">

            <!-- body -->
            <div class="modal-body text-center">
                User(s) changed!
            </div><!-- end body -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end modal role ok -->

<!-- Choose role modal -->
<div class="modal fade" id="roleModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change role for selected users</h4>
            </div><!-- end header -->

            <!-- body -->
            <div class="modal-body">
                <form role="form" id="roleForm">
                    <div class="form-group">
                        <div class="dropdown">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" id="roleDropdownBtn">
                                Roles&nbsp;<span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-change-label" role="menu" id="roleModalSelector">
                            <?php
                                foreach ( $data[ 'roles_for_modification' ] as $role ) {
                                    
                                    echo '<li><a href="#" data-value="' . $role->name . '">' . $role->label . '</a></li>' . "\n";
                                }
                            ?>
                            </ul>
                        </div><!-- end dropdown div -->
                    </div><!-- end form group div -->
                </form><!-- end form -->
            </div><!-- end body -->

            <!-- footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary disabled" id="roleSubmitButton">Submit</button>
            </div><!-- end footer -->

        </div><!-- end content -->
    </div><!-- end dialog -->
</div><!-- end myModal -->

<p>
    <?= $data['board']->display(); ?>
</p>
