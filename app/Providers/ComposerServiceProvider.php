<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //current user to all views
        View::composer('*', "App\Http\ViewComposers\AllViewComposer");
        //accounts to views
        View::composer('components.selects.students', "App\Http\ViewComposers\StudentComponentComposer");
        //employees to views
        View::composer('components.selects.batches', "App\Http\ViewComposers\BatchComponentComposer");
        //branches to views
        View::composer('components.selects.courses', "App\Http\ViewComposers\CourseComponentComposer");
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
