<?php
namespace Admin42\Command\Setup;

use Admin42\Command\User\CreateCommand;
use Core42\Command\AbstractCommand;
use Core42\Command\ConsoleAwareTrait;
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
            'Email: ',
            false,
            100
        );

        $password = Password::prompt(
            'Password: ',
            false
        );

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
        // TODO: Implement consoleSetup() method.
    }
}
