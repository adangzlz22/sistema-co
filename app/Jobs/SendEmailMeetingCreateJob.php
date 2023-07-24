<?php

namespace App\Jobs;

use App\Mail\MeetingCreateNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailMeetingCreateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $entity;
    protected $cc;
    protected $modelo;
    public $tries = 2;
    public $timeout = 60;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($entity,$cc,$modelo)
    {
        $this->onQueue('quemails');
        $this->entity = $entity;
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
        try {
            Mail::to($this->entity->email)->cc($this->cc)->queue(new MeetingCreateNotification($this->entity,$this->modelo));
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
        
    }
}
