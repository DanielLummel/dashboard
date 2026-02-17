<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Setting $setting): bool
    {
        return $setting->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Setting $setting): bool
    {
        return $setting->user_id === $user->id;
    }

    public function delete(User $user, Setting $setting): bool
    {
        return false;
    }
}
