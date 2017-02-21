<?php
namespace Admin42;

return [
    'security' => [
        'csp' => [
            'nonce'         => true,
            'style_src'     => ["'unsafe-inline'"],
            'script_src'    => ['https://www.gstatic.com']
        ],
    ],
];


