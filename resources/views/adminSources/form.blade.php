@php
    /** @var \Domain\Source\Models\Source $source */
@endphp

@component('layouts.admin', [
    'title' => $source->getName(),
])
    <heading>
        {{ $source->getName() }}
    </heading>

    <div class="mt-2">
        <a
            class="link text-grey-darker"
            href="http://{{ $source->website }}"
        >
            {{ __('Website') }}</a>&nbsp;–
        <a
            class="link text-grey-darker"
            href="{{ $source->url }}"
        >
            {{ __('RSS') }}</a>&nbsp;–
        <a
            class="link text-grey-darker"
            href="{{ action([\App\Http\Controllers\PostsController::class, 'source'], $source->website) }}"
        >
            {{ __('Filtered') }}
        </a>
    </div>

    @if($viewsPerDay->isNotEmpty())
        <div class="mt-2">
            <views-chart :views-per-day="$viewsPerDay" :votes-per-day="$votesPerDay"></views-chart>
        </div>
    @endif

    <form-component
        class="mt-8"
        :action="action([\App\Http\Controllers\AdminSourcesController::class, 'update'], $source)"
    >
        <div class="w-3/5">
            <text-field
                name="url"
                :label="__('RSS url')"
                :initial-value="$url"
            ></text-field>

            <text-field
                name="twitter_handle"
                :label="__('Twitter handle (optional)')"
                :initial-value="$twitterHandle"
            ></text-field>

            <checkboxes-field
                class="mt-2"
                name="topic_ids[]"
                :label="__('Topics')"
                :options="$topicOptions"
                :initial-values="$topics"
            ></checkboxes-field>

            <checkbox-field
                name="is_active"
                :label="__('Is active')"
                :initial-value="$isActive"
            ></checkbox-field>

            <checkbox-field
                name="is_validated"
                :label="__('Is validated')"
                :initial-value="$isValidated"
            ></checkbox-field>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div>
                <submit-button class="mt-3">
                    {{ __('Save') }}
                </submit-button>

                <a class="ml-2" href="{{ action([\App\Http\Controllers\AdminSourcesController::class, 'index']) }}">
                    {{ __('Back') }}
                </a>
            </div>

            <div>
                <a
                    href="{{ action([\App\Http\Controllers\AdminSourcesController::class, 'confirmDelete'], $source) }}"
                    class="ml-2 text-red font-bold"
                >
                    {{ __('Delete') }}
                </a>
            </div>
        </div>
    </form-component>
@endcomponent
