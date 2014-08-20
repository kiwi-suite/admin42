<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                array(
                    'options' => array(
                        'label' => 'Settings',
                        'icon' => 'fa fa-cog'
                    ),
                    'pages' => array(
                        array(
                            'options' => array(
                                'label' => 'User',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users',
                            ),
                            'pages' => array(
                                array(
                                    'options' => array(
                                        'label' => 'Add User',
                                        'route' => 'admin/user/add',
                                        'icon' => 'fa fa-users',
                                        'params' => array(
                                            'isEditMode' => false
                                        )
                                    ),
                                ),
                                array(
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
