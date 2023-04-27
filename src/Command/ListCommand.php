<?php

namespace FriendsOfWp\GistDevCliExtension\Command;

use FriendsOfWp\DeveloperCli\Command\Command;
use FriendsOfWp\DeveloperCli\Util\OutputHelper;
use FriendsOfWp\GistDevCliExtension\Util\GistHelper;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ListCommand extends Command
{
    const DEFAULT_CONFIG_FILE = __DIR__ . '/../../config/default.yml';

    protected static $defaultName = 'commands:gist:list';
    protected static $defaultDescription = 'List all command that are registered as gist from GitHub.';

    protected function configure(): void
    {
        $this->addOption('configFile', 'c', InputOption::VALUE_OPTIONAL, 'The configuration file.', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        OutputHelper::writeInfoBox($output, "This command lists all commands that are attached to gists.");

        $config = $this->geConfig();

        $gistHelper = new GistHelper();
        $gists = [];

        foreach ($config['repositories'] as $repository) {
            $gists = array_merge($gistHelper->getGists('friends-of-wp'), $gists);
        }

        $this->renderTable($output, ['Identifier', 'Description'], $gists);

        return SymfonyCommand::SUCCESS;
    }

    private function geConfig(): array
    {
        return Yaml::parse(file_get_contents(self::DEFAULT_CONFIG_FILE), true);
    }
}
