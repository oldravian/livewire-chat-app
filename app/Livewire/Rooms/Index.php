<?php

declare(strict_types=1);

namespace App\Livewire\Rooms;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

#[On('room-created')]
class Index extends Component
{
    public ?int $activeRoomId = null;

    #[On('room-selected')]
    public function getActiveRoomId(int $id): void
    {
        $this->activeRoomId = $id;
    }

    public function render(): View
    {
        return view('livewire.rooms.index', [
            'rooms' => Room::query()
                ->whereRelation('users', 'users.id', auth()->id())
                ->latest()
                ->get(),
        ]);
    }
}
