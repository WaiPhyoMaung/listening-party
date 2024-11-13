<?php

namespace App\Jobs;

use App\Models\Episode;
use App\Models\Podcast;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPodcastUrl implements ShouldQueue
{
    use Queueable;
    
    public $rssUrl;
    public $listeningParty;
    public $episode;
    /**
     * Create a new job instance.
     */
    public function __construct($rssUrl, $listeningParty, $episode)
    {
        $this->rssUrl = $rssUrl;
        $this->listeningParty = $listeningParty;
        $this->episode = $episode;
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
        $xml = simplexml_load_file($this->rssUrl);
        

        $podcastTitle = $xml->channel->title;
        $podcastArtworkUrl = $xml->channel->image->url;
        $latestEpisode = $xml->channel->item[0];
        $episodeTitle = $latestEpisode->title;
        $episodeMediaUrl = (string) $latestEpisode->enclosure['url'];
        
        //register itunes namespace to grab the episode length
        $namespaces = $xml->getNamespaces(true);
        $itunesNamespace = $namespaces['itunes'];
        $episodeLength = $latestEpisode->children($itunesNamespace)->duration;

        $interval = CarbonInterval::createFromFormat('H:i:s', $episodeLength);
        $endTime = $this->listeningParty->start_time->add($interval);

        //save these to the database
        //create the podcast and then update the episode to be linked to the podcast
        $podcast = Podcast::updateOrCreate([
            'title' => $podcastTitle,
            'artwork_url' => $podcastArtworkUrl,
            'rss_url' => $this->rssUrl,
        ]);
        
        $this->episode->podcast()->associate($podcast);

        $this->episode->update([
            'title' => $episodeTitle,
            'media_url' => $episodeMediaUrl,
        ]);

        $this->listeningParty->update([
            'end_time' => $endTime,
        ]);

        
    }
}
