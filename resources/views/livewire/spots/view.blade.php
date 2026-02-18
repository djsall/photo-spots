<div class="flex justify-center">
    <div class="m-4 md:m-24 text-center w-full max-w-[1200px]">
        <h1 class="text-4xl md:text-5xl font-semibold mt-8 md:mt-0">
            {{ $this->spot->name }}
        </h1>

        <div class="flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 my-4 md:my-12 gap-2">
                @foreach($this->images() as $image)
                    <a href="{{ $image }}" target="_blank">
                        <img
                            class="w-56"
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
            </p> <p class="text-xl underline">
                @lang('views.spots.view.difficulty'):
            </p>
            <p class="text-xl">
                {{ $this->spot->difficulty }}
            </p>
        </div>
    </div>
</div>
