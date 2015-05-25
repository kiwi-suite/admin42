<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                'user' => array(
                    'options' => array(
                        'label' => 'label.users',
                        'route' => 'admin/user',
                        'icon' => 'fa fa-users fa-fw',
                        'order' => 10000,
                    ),
                ),
            ),
        ),
        'listeners' => array(
            'admin42' => array(
                'Admin42\Navigation\Listener\RbacListener'
            ),
        ),
    ),
);
