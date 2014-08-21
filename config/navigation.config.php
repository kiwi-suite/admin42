<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                'settings' => array(
                    'options' => array(
                        'label' => 'label.settings',
                        'icon' => 'fa fa-gears fa-fw'
                    ),
                    'pages' => array(
                        'user' => array(
                            'options' => array(
                                'label' => 'label.users',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users fa-fw',
                                'order' => 100,
                            ),
                            'pages' => array(
                                'add' => array(
                                    'options' => array(
                                        'label' => 'label.user-add',
                                        'route' => 'admin/user/add',
                                        'icon' => 'fa fa-users fa-fw',
                                        'params' => array(
                                            'isEditMode' => false
                                        )
                                    ),
                                ),
                                'edit' => array(
                                    'options' => array(
                                        'label' => 'label.user-edit',
                                        'route' => 'admin/user/edit',
                                        'icon' => 'fa fa-users fa-fw',
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
