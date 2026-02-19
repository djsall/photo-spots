<?php

namespace App\Livewire\Spots;

use App\Models\Spot;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;

class View extends Component
{
    public Spot $spot;

    public function mount(): void
    {
        $this->spot->loadMissing(['categories:id,name', 'techniques:id,name', 'environmentalFactors:id,name']);
    }

    #[Computed]
    public function images(): array
    {
        return collect($this->spot->images ?? [])
            ->map(fn (string $path) => Storage::url($path))
            ->toArray();
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.spots.view');
    }
}
