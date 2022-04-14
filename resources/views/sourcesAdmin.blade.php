<?php
/** @var \Illuminate\Support\Collection|\App\Models\Source[] $sources */
?>

@component('layout.app')

    <div class="mx-auto md:w-3/4 xl:w-1/3 w-full grid gap-4 mt-4">
        <div class="text-sm text-center text-gray-400">
            <a class="underline hover:no-underline" href="{{ action(\App\Http\Controllers\HomeController::class) }}">
                back
            </a>
        </div>

        <div class="flex mx-4 shadow-md">
            <div class="px-4 py-1 grow text-center bg-gray-200">pending</div>
            <div class="px-4 py-1 grow text-center bg-blue-100">publishing</div>
            <div class="px-4 py-1 grow text-center bg-white">published</div>
            <div class="px-4 py-1 grow text-center bg-orange-100">duplicate</div>
            <div class="px-4 py-1 grow text-center bg-red-100">denied</div>
        </div>

        <div class="bg-white mx-4 shadow-md grid">

            @foreach ($sources as $source)
                <div>
                    <div
                        class="
                                block px-12 p-4
                                {{ $source->isPublishing() ? 'bg-blue-100' : '' }}
                                {{ $source->isPending() ? 'bg-gray-200' : '' }}
                                {{ $source->isDenied() ? 'bg-red-100' : '' }}
                                {{ $source->isDuplicate() ? 'bg-orange-100' : '' }}
                            "
                    >
                        <h1 class="font-bold">
                            {{ $source->name }}
                            <span class="text-sm font-normal">— {{ $source->url }}</span>
                        </h1>

                        <div class="flex gap-2 text-sm pt-2">
                            <a href="{{ $source->getBaseUrl() }}"
                               class="underline hover:no-underline mr-4 py-2"
                               target="_blank" rel="noopener noreferrer"
                            >
                                Show
                            </a>

                            @if($source->canPublish())
                                <a href="{{ action(\App\Http\Controllers\PublishSourceController::class, $source) }}"
                                   class="underline hover:no-underline text-green-600 mr-4 py-2"
                                >
                                    Publish
                                </a>
                            @endif

                            @if($source->canDeny())
                                <a href="{{ action(\App\Http\Controllers\DenySourceController::class, $source) }}"
                                   class="underline hover:no-underline text-red-600 py-2"
                                >
                                    Deny
                                </a>
                            @endif

                            @if($source->canDelete())
                                <a href="{{ action(\App\Http\Controllers\DeleteSourceController::class, $source) }}"
                                   class="underline hover:no-underline text-red-600 py-2"
                                >
                                    Delete
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
@endcomponent
