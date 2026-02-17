<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Services\TimeEntryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit(Request $request, TimeEntryService $timeEntryService): View
    {
        $setting = $timeEntryService->resolveSetting($request->user());
        $this->authorize('view', $setting);

        return view('settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(UpdateSettingRequest $request, TimeEntryService $timeEntryService): RedirectResponse
    {
        $setting = $timeEntryService->resolveSetting($request->user());
        $this->authorize('update', $setting);

        $setting->update($request->validated());

        return redirect()
            ->route('settings.edit')
            ->with('status', 'Einstellungen gespeichert.');
    }
}
