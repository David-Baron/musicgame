<?php 
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class GithubService
{
    protected $owner;
    protected $repos;
    protected $compressor = 'zip';
    protected $latestReleaseTag = null;
    protected $tarPath;
    protected $zipPath;
    

    public function __construct(string $owner, string $repos, string $compressor)
    {
        $this->owner = $owner;
        $this->repos = $repos;
        $this->compressor = $compressor;
    }

    public function latestReleaseVersion()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.github.com/repos/'.$this->owner.'/'.$this->repos.'/releases');

        $statusCode = $response->getStatusCode();
        if ($statusCode === 200) {
            $latest = $response->toArray()[0];
            $this->latestReleaseTag = $latest['tag_name'];
            $this->tarPath = $latest['tarball_url'];
            $this->zipPath = $latest['zipball_url'];
            return $this->latestReleaseTag;
        }

        // Responses are lazy: this code is executed as soon as headers are received
        /* if (200 !== $response->getStatusCode()) {
            throw new \Exception('The github repository is not accessible!');
        } */

        return false;
    }
}