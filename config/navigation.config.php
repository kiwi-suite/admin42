<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                'content' => [
                    'options' => array(
                        'label' => 'label.content',
                        'order' => 1000,
                        'permission' => 'navigation/section/content',
                    ),
                    'pages' => [
                        'media' => array(
                            'options' => array(
                                'label' => 'label.media',
                                'route' => 'admin/media',
                                'icon' => 'fa fa-picture-o fa-fw',
                                'order' => 5000,
                                'permission' => 'route/admin/media'
                            ),
                        ),
                    ]
                ],
                'setting' => [
                    'options' => array(
                        'label' => 'label.settings',
                        'order' => 10000,
                        'permission' => 'navigation/section/settings',
                    ),
                    'pages' => [
                        'user' => array(
                            'options' => array(
                                'label' => 'label.users',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users fa-fw',
                                'order' => 10000,
                                'permission' => 'route/admin/user'
                            ),
                        ),
                    ]
                ]
            ),
        ),
        'listeners' => array(
            'admin42' => array(
                'Admin42\Navigation\Listener\RbacListener'
            ),
        ),
    ),
);
