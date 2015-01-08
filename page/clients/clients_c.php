<?php

// Manage clients
abstract class Clients_PAG_C extends Base_PAG_C
{
    static protected function process()
    {
        $board = new Board_LIB( 'clients',
                                'page/clients/fields.csv',
                                'page/clients/actions.csv',
                                static::$model->getBoardQuery(),
                                static::$model->getBoardDefaultSort(),
                                'No clients' );

        // Send board to view
        static::$view->assign('board', $board);
        static::$view->assign('roles_for_modification', static::$model->getRolesForModification());
    }
}
