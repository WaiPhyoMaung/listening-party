<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;
new class extends Component {

    #[Validate('required|string|max:255')]
    public string $name = '';
    
    #[Validate('required|url')]
    public string $mediaUrl = '';
    
    #[Validate('required|date')]
    public $start_time;
    
    public function createListeningParty()
    {
        $this->validate();
        
        $episode = \App\Models\Episode::create([
            'media_url' => $this->mediaUrl,
        ]);
        
        $listeningParty = \App\Models\ListeningParty::create([
            'episode_id' => $episode->id,
            'name' => $this->name,
            'start_time' => $this->start_time,
        ]);
        // dd($listeningParty);
        $this->redirect(route('parties.show', $listeningParty));
    }
    public function with()
    {
        return [
            'listening_parties'=> \App\Models\ListeningParty::all(),
        ];
    }
}; ?>

<div class="flex items-center justify-center min-h-screen bg-slate-50">
    <div class="max-w-lg w-full px-4 ">
        <form wire:submit="createListeningParty" action="" class="space-y-6">
            <x-input wire:model="name" placeholder="Listening Party Name"/>
            <x-input wire:model="mediaUrl" placeholder="Podcast Listening Party URL" description="Direct Episode Link or Youtube Link. RSS Feeds will grab the latest episode."/>
            <x-datetime-picker wire:model="start_time" placeholder="Listening Party Start Time"/>
            <x-button type="submit" primary>Create Listening Party</x-button>
        </form>
    </div>
</div>
