<?php

namespace App\Livewire\Spots;

use App\Models\Spot;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class View extends Component
{
    public Spot $spot;

    public function mount(): void
    {
        $this->spot->loadMissing(['categories:id,name', 'techniques:id,name', 'environmentalFactors:id,name']);
    }

    public function images(): array
    {
        $images = $this->spot->images;

        if ($images === null) {
            return [];
        }

        return array_map(fn (string $image) => Storage::url($image), $this->spot->images);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.spots.view');
    }
}
