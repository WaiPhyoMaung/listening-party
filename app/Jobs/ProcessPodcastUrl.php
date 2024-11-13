<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPodcastUrl implements ShouldQueue
{
    use Queueable;
    
    public $rssUrl;

    /**
     * Create a new job instance.
     */
    public function __construct($rssUrl)
    {
        $this->rssUrl = $rssUrl;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //grab the podcast name
        //grab the latest episode
        //add the latest episode media url to the existing episode
        //update the existing episode's media url to the latest episode media url
        //find the episode length and set the listening end time  to the start time + episode length
    }
}
