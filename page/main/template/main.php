<div class="container">
    <p>
    <table class="table table-striped table-hover table-bordered table-condensed">
        <thead>
            <tr>
                <th>Page</th>
                <th>Shortcut</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach( Session_Manager_LIB::getUserPages() as $page ) {
            ?>
            <tr>
                <td><?= $page['headerTitle'] ?></td>
                <td><?= ( $page['withCtrl'] ? 'Ctrl+' : '' ) . $page['shortcut'] ?></td>
                <td><?= $page['description'] ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </p>
</div>
