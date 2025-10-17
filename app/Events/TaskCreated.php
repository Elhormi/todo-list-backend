<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcast // <-- 1. Implémentez ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task; // <-- 2. Propriété publique pour la tâche

    /**
     * Create a new event instance.
     */
    public function __construct(Task $task) // <-- 3. Acceptez la tâche dans le constructeur
    {
        $this->task = $task;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // 4. Définissez un canal privé pour chaque utilisateur
        return [
            new PrivateChannel('notifications.' . $this->task->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        // 5. (Optionnel) Donnez un nom clair à l'événement
        return 'task.created';
    }
}
