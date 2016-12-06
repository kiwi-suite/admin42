<?php
namespace Admin42;

use Admin42\Command\Setup\CreateUserSetupCommand;

return [
    'cli_setup_commands' => [
        CreateUserSetupCommand::class
    ],
];
