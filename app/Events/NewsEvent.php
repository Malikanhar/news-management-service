<?php

namespace App\Events;

use App\Models\News;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private News $news;
    private $action;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(News $news, string $action)
    {
        $this->news = $news;
        $this->action = $action;
    }

    public function getNews()
    {
        return $this->news;
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
