@component('layouts.app', [
    'title' => __('Make a suggestion'),
])
    <h1 class="font-title text-2xl mt-4 mb-8">
        {{ __('Suggest a blog') }}
    </h1>

    <p>
        {!! __("
            Thanks for your suggestion! We'll review this RSS feed and add it if possible!
            Meanwhile, you can read lots of good <a href=\":url\" class=\"link\">here</a>.
        ", [
            'url' => action([\App\Http\Controllers\PostsController::class, 'index'])
        ]) !!}
    </p>
@endcomponent
