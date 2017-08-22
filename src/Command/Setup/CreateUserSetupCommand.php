<?php

/*
 * admin42
 *
 * @package admin42
 * @link https://github.com/kiwi-suite/admin42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <tech@kiwi-suite.com>
 */

namespace Admin42\Command\Setup;

use Admin42\Command\User\CreateCommand;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
use Zend\Console\Console;
use Zend\Console\Prompt\Line;
use Zend\Console\Prompt\Password;
use ZF\Console\Route;

class CreateUserSetupCommand extends AbstractCommand
{
    use ConsoleAwareTrait;

    /**
     * @return mixed
     */
    protected function execute()
    {
        $this->consoleOutput("<info>Create admin user</info>");

        do {
            try {
                $config = $this->ask();

                /** @var CreateCommand $cmd */
                $cmd = $this->getCommand(CreateCommand::class);
                $cmd->setEmail($config['email'])
                    ->setPassword($config['password'])
                    ->setRole($config['role'])
                    ->run();

                $hasInfos = !$cmd->hasErrors();
            } catch (\Exception $e) {
                $this->consoleOutput("<error>Can't create user. Please try again</error>");
                $this->consoleOutput("");

                $hasInfos = false;
            }
        } while (!$hasInfos);
    }

    protected function ask()
    {
        $email = Line::prompt(
            'E-Mail: ',
            false,
            100
        );

        if (Console::isWindows()) {
            $this->consoleOutput("because of the limitations of the windows console the plain password will be visible in the console!");
            $this->consoleOutput("be careful!\n");

            $passwordPrompt = new Line('password: ');
            $passwordRepeatPrompt = new Line('repeat password: ');
        } else {
            $passwordPrompt = new Password('password: ');
            $passwordRepeatPrompt = new Password('repeat password: ');
        }

        do {
            $password = $passwordPrompt->show();
            $passwordRepeat = $passwordRepeatPrompt->show();

            if ($password == $passwordRepeat) {
                break;
            }

            $this->consoleOutput("password does not match, please try again!\n");
        } while (true);

        $config = [
            'email'     => $email,
            'password'  => $password,
            'role'      => 'admin',
        ];

        return $config;
    }

    /**
     * @param Route $route
     * @return void
     */
    public function consoleSetup(Route $route)
    {
    }
}
