<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                'settings' => array(
                    'options' => array(
                        'label' => 'Settings',
                        'icon' => 'fa fa-gears'
                    ),
                    'pages' => array(
                        'user' => array(
                            'options' => array(
                                'label' => 'User',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users',
                                'order' => 100,
                            ),
                            'pages' => array(
                                'add' => array(
                                    'options' => array(
                                        'label' => 'Add User',
                                        'route' => 'admin/user/add',
                                        'icon' => 'fa fa-users',
                                        'params' => array(
                                            'isEditMode' => false
                                        )
                                    ),
                                ),
                                'edit' => array(
                                    'options' => array(
                                        'label' => 'Edit User',
                                        'route' => 'admin/user/edit',
                                        'icon' => 'fa fa-users',
                                        'params' => array(
                                            'isEditMode' => true
                                        ),
                                        'ignore_params' => array(
                                            'id'
                                        )
                                    ),
                                )
                            )
                        )
                    )
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
