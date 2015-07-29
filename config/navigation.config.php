<?php

return array(
    'navigation' => array(
        'containers' => array(
            'admin42' => array(
                'content' => [
                    'options' => array(
                        'label' => 'label.content',
                        'options' => [
                            'order' => 1000,
                        ]
                    ),
                    'pages' => [
                        'media' => array(
                            'options' => array(
                                'label' => 'label.media',
                                'route' => 'admin/media',
                                'icon' => 'fa fa-picture-o fa-fw',
                                'order' => 5000,
                            ),
                        ),
                    ]
                ],
                'setting' => [
                    'options' => array(
                        'label' => 'label.settings',
                        'options' => [
                            'order' => 10000,
                        ]
                    ),
                    'pages' => [
                        'user' => array(
                            'options' => array(
                                'label' => 'label.users',
                                'route' => 'admin/user',
                                'icon' => 'fa fa-users fa-fw',
                                'order' => 10000,
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
