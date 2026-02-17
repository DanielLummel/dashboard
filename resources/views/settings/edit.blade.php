<x-app-layout>
    <div class="mx-auto max-w-2xl panel p-6">
        <h1 class="text-2xl font-semibold text-slate-900">Settings</h1>
        <p class="mt-1 text-sm text-slate-600">Rundung und Zeitverhalten fürs Tracking.</p>

        <form method="POST" action="{{ route('settings.update') }}" class="mt-6 space-y-4">
            @csrf
            @method('PATCH')

            <div>
                <label for="rounding_minutes" class="mb-1 block text-sm font-medium text-slate-700">Rundung (Minuten)</label>
                <select id="rounding_minutes" name="rounding_minutes" class="select-base">
                    @foreach ([5, 10, 15, 30] as $step)
                        <option value="{{ $step }}" @selected((int) old('rounding_minutes', $setting->rounding_minutes) === $step)>{{ $step }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="week_start" class="mb-1 block text-sm font-medium text-slate-700">Wochenstart</label>
                <select id="week_start" name="week_start" class="select-base">
                    <option value="Mon" @selected(old('week_start', $setting->week_start) === 'Mon')>Montag</option>
                    <option value="Sun" @selected(old('week_start', $setting->week_start) === 'Sun')>Sonntag</option>
                </select>
            </div>

            <div>
                <label for="timezone" class="mb-1 block text-sm font-medium text-slate-700">Zeitzone</label>
                <input id="timezone" name="timezone" type="text" class="input-base" value="{{ old('timezone', $setting->timezone) }}" placeholder="Europe/Berlin">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary">Speichern</button>
            </div>
        </form>
    </div>
</x-app-layout>
