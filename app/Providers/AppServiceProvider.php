<?php

namespace App\Providers;

use App\Models\Note;
use App\Models\Project;
use App\Models\Setting;
use App\Models\Snippet;
use App\Models\TimeEntry;
use App\Policies\NotePolicy;
use App\Policies\ProjectPolicy;
use App\Policies\SettingPolicy;
use App\Policies\SnippetPolicy;
use App\Policies\TimeEntryPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
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
        Gate::policy(Project::class, ProjectPolicy::class);
        Gate::policy(Note::class, NotePolicy::class);
        Gate::policy(Snippet::class, SnippetPolicy::class);
        Gate::policy(TimeEntry::class, TimeEntryPolicy::class);
        Gate::policy(Setting::class, SettingPolicy::class);

        View::composer('layouts.*', function ($view): void {
            if (! Auth::check()) {
                return;
            }

            $user = Auth::user();

            $view->with('sidebarProjects', $user->projects()->orderBy('name')->get());
            $view->with('sidebarRunningTimer', $user->timeEntries()->running()->with('project')->first());
        });
    }
}
