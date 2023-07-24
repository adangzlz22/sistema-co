<?php

namespace App\Jobs;

use App\Mail\MeetingAfterStartNotification;
use App\Mail\MeetingCreateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailMeetingAfterStartJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $correos;
    protected $cc;
    protected $modelo;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($correos,$cc,$modelo)
    {
        $this->correos = $correos;
        $this->cc = $cc;
        $this->modelo = $modelo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->correos)->send(new MeetingAfterStartNotification($this->modelo));
    }
}
