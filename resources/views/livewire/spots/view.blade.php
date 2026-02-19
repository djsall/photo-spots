<div class="flex justify-center">
    <div class="m-4 md:m-24 text-center w-full max-w-[1200px]">
        <h1 class="text-4xl md:text-5xl font-semibold mt-8 md:mt-0">
            {{ $this->spot->name }}
        </h1>

        <div class="flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 my-4 md:my-12 gap-2">
                @foreach($this->images() as $image)
                    <a href="{{ $image }}" target="_blank">
                        <img
                            class="w-56 lg:w-128"
                            src="{{ $image }}"
                            alt="{{ $this->spot->name }} {{ $loop->index + 1 }}"
                        >
                    </a>
                @endforeach
            </div>
        </div>

        <div class="text-left">
            <p class="text-xl underline">
                @lang('views.spots.view.description'):
            </p>
            <p class="text-xl mb-4">
                {{ $this->spot->description }}
            </p>
            <p class="text-xl underline">
                @lang('views.spots.view.access'):
            </p>
            <p class="text-xl mb-4">
                {{ $this->spot->access }}
            </p>
            <p class="text-xl underline">
                @lang('views.spots.view.difficulty'):
            </p>
            <p class="text-xl">
                {{ $this->spot->difficulty }}
            </p>
        </div>

        <div class="mt-12 opacity-80 italic">
            @if(filled($categories = $this->spot->categories))
                <div class="flex gap-1 mb-1">
                    <p>
                        @lang('views.spots.view.categories'):
                    </p>
                    @foreach($categories as $category)
                        <p>
                            {{ $category->name }}@if(! $loop->last)
                                ,
                            @endif
                        </p>
                    @endforeach
                </div>
            @endif
            @if(filled($techniques = $this->spot->techniques))
                <div class="flex gap-1 mb-1">
                    <p>
                        @lang('views.spots.view.techniques'):
                    </p>
                    @foreach($techniques as $technique)
                        <p>
                            {{ $technique->name }}@if(! $loop->last)
                                ,
                            @endif
                        </p>
                    @endforeach
                </div>
            @endif
            @if(filled($environmentalFactors = $this->spot->environmentalFactors))
                <div class="flex gap-1">
                    <p>
                        @lang('views.spots.view.environmental-factors'):
                    </p>
                    @foreach($environmentalFactors as $environemntalFactor)
                        <p>
                            {{ $environemntalFactor->name }}@if(! $loop->last)
                                ,
                            @endif
                        </p>
                    @endforeach
                </div>
            @endif
            @if($created_by = $this->spot->user?->name)
                <div class="flex gap-1">
                    <p>
                        @lang('views.spots.view.created-by'):
                    </p>
                    <p>
                        {{ $created_by }}
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
