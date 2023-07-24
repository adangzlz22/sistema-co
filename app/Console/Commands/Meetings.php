<?php

namespace App\Console\Commands;

use App\Helpers\HelperApp;
use App\Jobs\SendEmailMeetingAfterStartJob;
use App\Listeners\SendMeetingNotification;
use App\Mail\MeetingAfterStartNotification;
use App\Mail\MeetingStartNotification;
use App\Models\Meeting;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class Meetings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:meetings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envío de correos de notificación para reuniones';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateMeetings();
        $this->envioCorreosMeetingStart();
        $this->envioCorreosMeetingAfterStart();
    }

    private function envioCorreosMeetingStart()
    {
        try {
            $datenow=Carbon::now()->toDateString();
            $dateaddfivemin = (Carbon::now()->addMinutes(120))->toTimeString('minute');
            
            $meetings=Meeting::with(['meeting_type','modality','place','status'])->where('status_id',Status::POR_CELEBRAR)->where('meeting_date', $datenow)->where('meeting_time','like',$dateaddfivemin.'%')->get();
            foreach ($meetings as $meeting) {
                try {
                    $correos = $meeting->meeting_type->entities->pluck('email');
                    $cc = User::whereIn('entity_id',$meeting->meeting_type->entities->pluck('id'))->whereNotIn('email',$correos)->get()->pluck('email');
                    Mail::to($correos)
                    ->cc($cc)
                    ->send(new MeetingStartNotification($meeting));
                    (new SendMeetingNotification())->handle($meeting,1);
                } catch (\Throwable $th) {
                    \Log::info($th->getMessage()." | ".$th->getFile()." | ".$th->getCode());
                }
            }
        } catch (\Throwable $th) {
            \Log::info($th->getMessage()." | ".$th->getFile()." | ".$th->getCode());
        }
    }

    private function envioCorreosMeetingAfterStart()
    {
        try {
            $datenow=Carbon::now()->toDateString();
            $dateaddfivemin = (Carbon::now()->subMinutes(1))->toTimeString('minute');
            
            // $up = Meeting::where('status_id',Status::POR_CELEBRAR)->where('meeting_date', $datenow)->where('meeting_time','like',$dateaddfivemin.'%')->update(['status_id'=>Status::EN_PROCESO]);
            
            // if($up){
                $meetings=Meeting::with(['meeting_type','modality','place','status'])->where('status_id',Status::POR_CELEBRAR)->where('meeting_date', $datenow)->where('meeting_time','like',$dateaddfivemin.'%')->get();
                foreach ($meetings as $meeting) {
                    try {
                        $users = User::whereHas('roles', function ($query) {
                            $query->where('name', HelperApp::$roleSuperUsuario);
                        })->get()->pluck('email');
                        Mail::to($users)->send(new MeetingAfterStartNotification($meeting));
                        // dispatch(new SendEmailMeetingAfterStartJob($users,null,$meeting));
                        (new SendMeetingNotification())->handle($meeting,2);
                    } catch (\Throwable $th) {
                        \Log::info($th->getMessage()." | ".$th->getFile()." | ".$th->getCode());
                    }
                }
            // }
        } catch (\Throwable $th) {
            \Log::info($th->getMessage()." | ".$th->getFile()." | ".$th->getCode());
        }
    }

    private function updateMeetings()
    {
        try {
            $datenow=Carbon::now()->toDateString();
            $time = Carbon::now()->toTimeString();
            $up = Meeting::where('status_id',Status::POR_CELEBRAR)->whereDate('meeting_date', $datenow)->where('meeting_time_end','<',$time)->update(['status_id'=>Status::CELEBRADA]);
        } catch (\Throwable $th) {
            \Log::info($th->getMessage()." | ".$th->getFile()." | ".$th->getCode());
        }
    }
}
