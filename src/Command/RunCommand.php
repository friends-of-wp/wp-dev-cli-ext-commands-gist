<?php

namespace FriendsOfWp\GistDevCliExtension\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class RunCommand extends GistCommand
{
    protected static $defaultName = 'commands:gist:run';
    protected static $defaultDescription = 'Run a command that is hosted on gist.';

    protected function configure()
    {
        $this->addArgument('identifier', InputArgument::REQUIRED, 'The commands identifier.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gist = $this->showGist($output, $input->getArgument('identifier'));

        $questionHelper = $this->getHelper('question');
        $doTheRun = $questionHelper->ask($input, $output, new ConfirmationQuestion('  Are you sure you want to run that command? [y/n] ', true));

        if ($doTheRun) {
            $this->executeCommand($gist['content']);
        }

        return SymfonyCommand::SUCCESS;
    }

    private function executeCommand(string $command): void
    {
        exec($command);
    }
}
