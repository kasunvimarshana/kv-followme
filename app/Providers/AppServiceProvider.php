<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Queue;

use Illuminate\Support\Facades\Blade;

use App\TW;
use App\Observers\TWObserver;

use App\Helpers\NotifyHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // Register the NotifyHelper as a singleton
        $this->app->singleton('notify', function () {
            return new NotifyHelper();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        /*
        Solve
        Illuminate\Database\QueryException  : SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long;
        */
        Schema::defaultStringLength(191);

        /*Queue::failing(function ($connection, $job, $data) {
            Log::error('Job failed!');
        });*/
        TW::observe(TWObserver::class);

        //
        // Optional: Blade directives
        Blade::directive('notify', function () {
            return '<?php if (App\Helpers\NotifyHelper::ready()): ?>';
        });

        Blade::directive('endnotify', function () {
            return '<?php endif; ?>';
        });
    }
}
