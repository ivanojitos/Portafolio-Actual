<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use App\Models\Profile;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::preventLazyLoading(
            ! app()->isProduction()
        );

        Relation::enforceMorphMap([
            'profile' => Profile::class,
            'project' => Project::class,
        ]);
    }
}
