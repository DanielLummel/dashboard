<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Services\TimeEntryService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, TimeEntryService $timeEntryService): View
    {
        $user = $request->user();
        $setting = $timeEntryService->resolveSetting($user);
        $timezone = $setting->timezone;

        $todayStart = Carbon::now($timezone)->startOfDay()->utc();
        $todayEnd = Carbon::now($timezone)->endOfDay()->utc();

        $weekStart = Carbon::now($timezone)->startOfWeek($setting->week_start === 'Sun' ? Carbon::SUNDAY : Carbon::MONDAY)->utc();
        $weekEnd = Carbon::now($timezone)->endOfWeek($setting->week_start === 'Sun' ? Carbon::SUNDAY : Carbon::MONDAY)->utc();

        $runningTimer = $user->timeEntries()->running()->with('project')->first();

        $todayMinutes = (int) TimeEntry::query()
            ->where('user_id', $user->id)
            ->whereBetween('start_at', [$todayStart, $todayEnd])
            ->sum('duration_minutes');

        $weekMinutes = (int) TimeEntry::query()
            ->where('user_id', $user->id)
            ->whereBetween('start_at', [$weekStart, $weekEnd])
            ->sum('duration_minutes');

        return view('dashboard', [
            'stats' => [
                'projects' => $user->projects()->count(),
                'notes' => $user->notes()->count(),
                'snippets' => $user->snippets()->count(),
                'time_entries' => $user->timeEntries()->count(),
                'today_minutes' => $todayMinutes,
                'week_minutes' => $weekMinutes,
            ],
            'runningTimer' => $runningTimer,
            'projects' => $user->projects()->latest()->take(6)->get(),
            'recentNotes' => $user->notes()->latest('updated_at')->take(5)->get(),
            'recentSnippets' => $user->snippets()->latest()->take(5)->get(),
            'recentEntries' => $user->timeEntries()->with('project')->latest('start_at')->take(6)->get(),
        ]);
    }
}
