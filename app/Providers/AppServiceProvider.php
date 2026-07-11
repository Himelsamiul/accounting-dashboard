<?php

namespace App\Providers;

use App\Observers\ActivityObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /** Business models whose create/update/delete actions are recorded in the history log. */
    protected array $auditedModels = [
        \App\Models\Client::class,
        \App\Models\Project::class,
        \App\Models\Invoice::class,
        \App\Models\Bank::class,
        \App\Models\TeamMember::class,
        \App\Models\TeamPayment::class,
        \App\Models\Role::class,
        \App\Models\User::class,
        \App\Models\Review::class,
        \App\Models\ContactMessage::class,
        \App\Models\CodeRequest::class,
        \App\Models\Customer::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->auditedModels as $model) {
            $model::observe(ActivityObserver::class);
        }
    }
}
