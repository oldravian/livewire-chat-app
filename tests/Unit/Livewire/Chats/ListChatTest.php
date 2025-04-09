<?php

declare(strict_types=1);
use App\Livewire\Chats\ListChats;
use App\Models\Chat;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Livewire;

it('renders with room without chats', function () {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Livewire::actingAs($user)
        ->test(ListChats::class, ['roomId' => $room->id])
        ->assertViewHas('chats', new Collection)
        ->assertSee('No chats found');
});

it('renders with room with chats', function () {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    $chats = Chat::factory(3)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(ListChats::class, ['roomId' => $room->id])
        ->assertViewHas('chats', $chats)
        ->assertDontSee('No chats found');
});

it('dispatch the chats:loaded event if offset is greater than zero', function () {
    $user = User::factory()->create();
    $room = Room::factory()
        ->hasAttached($user, relationship: 'users')
        ->create();

    Chat::factory(1)
        ->for($room)
        ->for($user, 'user')
        ->create();

    $chats = Chat::factory(3)
        ->for($room)
        ->for($user, 'user')
        ->create();

    Livewire::actingAs($user)
        ->test(ListChats::class, ['roomId' => $room->id, 'offset' => 1])
        ->assertViewHas('chats', $chats)
        ->assertDispatched('chats:loaded');
});
