<?php

namespace App\Observers;

use App\Jobs\PollImageJob;
use App\Models\Poll;

class PollObserver
{
    /**
     * Handle the Poll "created" event.
     */
    public function created(Poll $poll): void
    {
        PollImageJob::dispatch($poll);
    }

    /**
     * Handle the Poll "updated" event.
     */
    public function updated(Poll $poll): void
    {
        //
    }

    /**
     * Handle the Poll "deleted" event.
     */
    public function deleted(Poll $poll): void
    {
        //
    }

    /**
     * Handle the Poll "restored" event.
     */
    public function restored(Poll $poll): void
    {
        //
    }

    /**
     * Handle the Poll "force deleted" event.
     */
    public function forceDeleted(Poll $poll): void
    {
        //
    }
}
