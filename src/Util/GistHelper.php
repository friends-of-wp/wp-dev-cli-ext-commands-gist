<?php

namespace FriendsOfWp\GistDevCliExtension\Util;

use GuzzleHttp\Client;

class GistHelper
{
    const GIST_URL = 'https://api.github.com/users/%s/gists';

    /**
     * Return all gists of specific GitHub user. If a prefix is defined we will only return gists
     * that description starts with the prefix. The default is "wpdev: ".
     */
    public function getGists(string $username, bool $includeFilename = false, string $prefix = 'wpdev: '): array
    {
        $usersGists = $this->getRawGists($username);

        $gists = [];

        foreach ($usersGists as $usersGist) {
            if (str_starts_with($usersGist['description'], $prefix)) {
                foreach ($usersGist['files'] as $file) {
                    $gist = [
                        'name' => $username . ':' . $file['filename'],
                        'description' => str_replace($prefix, '', $usersGist['description'])
                    ];

                    if ($includeFilename) {
                        $gist['file'] = $file['raw_url'];
                    }

                    $gists[] = $gist;
                }
            }
        }

        return $gists;
    }

    /**
     * Returns a raw array filled with the data that comes from the GitHub API.
     */
    private function getRawGists(string $username)
    {
        $client = new Client();
        $response = $client->get(sprintf(self::GIST_URL, $username));

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * Return the raw content of the given gist.
     */
    private function getRawContent(string $rawUrl): string
    {
        $client = new Client();
        $response = $client->get($rawUrl);
        return (string)$response->getBody();
    }

    /**
     * Return all information about the given gist.
     */
    public function getGist(string $identifier): array
    {
        $username = substr($identifier, 0, strpos($identifier, ':'));

        $gists = $this->getGists($username, true);

        foreach ($gists as $gist) {
            if ($gist['name'] == $identifier) {
                $gist['content'] = $this->getRawContent($gist['file']);
                return $gist;
            }
        }

        throw new \RuntimeException('No gist with identifier ' . $identifier . ' found.');
    }
}
