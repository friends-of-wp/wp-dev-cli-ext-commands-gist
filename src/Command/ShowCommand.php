<?php

namespace FriendsOfWp\GistDevCliExtension\Command;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends GistCommand
{
    protected static $defaultName = 'commands:gist:show';
    protected static $defaultDescription = 'Show a command that is hosted on gist.';

    protected function configure()
    {
        $this->addArgument('identifier', InputArgument::REQUIRED, 'The commands identifier.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showGist($output, $input->getArgument('identifier'));
        return SymfonyCommand::SUCCESS;
    }
}
