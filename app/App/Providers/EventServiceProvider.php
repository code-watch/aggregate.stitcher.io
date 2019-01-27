<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\EventProjector\Projectionist;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        /** @var \Spatie\EventProjector\Projectionist $projectionist */
        $projectionist = $this->app->get(Projectionist::class);

        $projectionist->addProjectors([
            \Domain\Source\Projectors\SourceProjector::class,
            \Domain\Mute\Projectors\MuteProjector::class,
        ]);

        $projectionist->addReactors([
            \Domain\Source\Reactors\SourceReactor::class,
        ]);
    }
}
