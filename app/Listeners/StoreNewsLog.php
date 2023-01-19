<?php

namespace App\Listeners;

use App\Events\NewsEvent;
use App\Models\NewsLog;

class StoreNewsLog
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NewsEvent  $event
     * @return void
     */
    public function handle(NewsEvent $event)
    {
        NewsLog::create([
            'news_id' => $event->getNews()->id,
            'action' => $event->getAction()
        ]);
    }
}
