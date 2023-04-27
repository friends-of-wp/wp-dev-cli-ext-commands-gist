<?php

namespace FriendsOfWp\GistDevCliExtension\Command;

use FriendsOfWp\DeveloperCli\Command\Command;
use FriendsOfWp\GistDevCliExtension\Util\GistHelper;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GistCommand extends Command
{
    protected function showGist(OutputInterface $output, $identifier): array
    {
        $gistHelper = new GistHelper();

        $gist = $gistHelper->getGist($identifier);

        $command = $gist['content'];

        $this->writeWarning($output, [
            "Be careful. Please only run command that you understand. We only have limited control",
            "of repositories that are not owned by the Friends of WP."
        ]);

        $output->writeln('');
        $output->writeln('  Command to be run:');
        $this->writeInfo($output, $command);
        $output->writeln('');

        return $gist;
    }
}
