<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="text-center">
        <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-gray-900">
            {{ $this->spot->name }}
        </h1>

        <div class="mt-8 md:mt-12">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($this->images as $image)
                    <a href="{{ $image }}" target="_blank"
                       class="group overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-indigo-500">
                        <img
                            src="{{ $image }}"
                            alt="{{ $this->spot->name }} gallery image {{ $loop->iteration }}"
                            class="aspect-square w-full object-cover group-hover:opacity-75 transition duration-200"
                            loading="lazy"
                        >
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-12 text-left max-w-4xl mx-auto">
            <dl class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                            @lang('views.spots.view.description')
                        </dt>
                        <dd class="mt-1 text-lg text-gray-900">
                            {{ $this->spot->description }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                            @lang('views.spots.view.access')
                        </dt>
                        <dd class="mt-1 text-lg text-gray-900">
                            {{ $this->spot->access }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 uppercase tracking-wider">
                            @lang('views.spots.view.difficulty')
                        </dt>
                        <dd class="mt-1 text-lg text-gray-900">
                            {{ $this->spot->difficulty }}
                        </dd>
                    </div>
                </div>
            </dl>
        </div>

        <footer class="mt-16 pt-8 border-t border-gray-200 text-sm text-gray-500 italic">
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2">
                @if($this->spot->categories->isNotEmpty())
                    <p>
                        <strong>@lang('views.spots.view.categories'):</strong>
                        {{ $this->spot->categories->pluck('name')->implode(', ') }}
                    </p>
                @endif

                @if($this->spot->techniques->isNotEmpty())
                    <p>
                        <strong>@lang('views.spots.view.techniques'):</strong>
                        {{ $this->spot->techniques->pluck('name')->implode(', ') }}
                    </p>
                @endif

                @if($this->spot->environmentalFactors->isNotEmpty())
                    <p>
                        <strong>@lang('views.spots.view.environmental-factors'):</strong>
                        {{ $this->spot->environmentalFactors->pluck('name')->implode(', ') }}
                    </p>
                @endif

                @if($created_by = $this->spot->user?->name)
                    <p>
                        <strong>@lang('views.spots.view.created-by'):</strong>
                        {{ $created_by }}
                    </p>
                @endif
            </div>
        </footer>
    </div>
</div>
