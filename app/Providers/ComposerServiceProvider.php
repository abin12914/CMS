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
        View::composer('components.selects.authorities', "App\Http\ViewComposers\AuthorityComponentComposer");
        View::composer('components.selects.certificates', "App\Http\ViewComposers\CertificateComponentComposer");
        View::composer('components.selects.addresses', "App\Http\ViewComposers\AddressComponentComposer");
        //universities to views
        View::composer('components.selects.universities', "App\Http\ViewComposers\UniversityComponentComposer");
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
