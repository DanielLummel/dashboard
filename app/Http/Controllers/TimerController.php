<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTimerRequest;
use App\Services\TimeEntryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class TimerController extends Controller
{
    public function start(StartTimerRequest $request, TimeEntryService $timeEntryService): RedirectResponse
    {
        try {
            $timeEntryService->startTimer($request->user(), $request->validated());
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors());
        }

        return redirect()
            ->route('time-entries.index')
            ->with('status', 'Timer gestartet.');
    }

    public function stop(TimeEntryService $timeEntryService): RedirectResponse
    {
        try {
            $timeEntryService->stopTimer(request()->user());
        } catch (ValidationException $exception) {
            return redirect()->back()->withErrors($exception->errors());
        }

        return redirect()
            ->route('time-entries.index')
            ->with('status', 'Timer gestoppt.');
    }
}
