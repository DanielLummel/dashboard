<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_open_another_users_note(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $note = Note::factory()->create([
            'user_id' => $owner->id,
            'title' => 'Private Knowledge',
        ]);

        $this->actingAs($intruder)
            ->get(route('notes.show', $note))
            ->assertForbidden();
    }
}
