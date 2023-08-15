<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\FlightSchedule;
use Illuminate\Console\Command;
use App\TransferSms;
use Carbon\Carbon;

class ReminderAlertNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client Trip reminder alert';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
    $schedulers = FlightSchedule::with(['callerType', 'alertTemplate', 'userDetails'])
            ->where('status', '=', 'active')
            ->get();
        
        // $flights=;
        
        foreach ($schedulers as $scheduler) {
            
            $current_timestamp = Carbon::now()->toDateTimeString();
            if ($scheduler->callerType) {
        
                if ($scheduler->alertTemplate) {
                    // echo 'alertTemplate>>' . $scheduler->alertTemplate->body . '<br>';
                    $startTime = Carbon::parse($current_timestamp);
                    $endTime = Carbon::parse($scheduler->departure_time);

                    // $totalDuration = $endTime->diffForHumans($startTime);
                    $totalDuration2 = $endTime->diffInMinutes($startTime);
                    $remaining_times = ($scheduler->callerType->name - $scheduler->counter) * 60;
                    
                    if ($scheduler->userDetails) {
                        if ($scheduler->userDetails) {
                            $formated_time = floor($remaining_times / 60).'hours and '.($remaining_times -   floor($remaining_times / 60) * 60)." minutes";
                            $sent_message="Hi ".$scheduler->userDetails->lastName.",\r\n".$scheduler->alertTemplate->title." \r\n Your departure time is  ".$scheduler->departure_time."\r\n"."Remaining Time is ".$formated_time."\r\n".$scheduler->alertTemplate->body;
                            
                            if ($remaining_times == $totalDuration2) {
                                $sms = new TransferSms();
                                        $sms->sendSMS($scheduler->userDetails->mobileTelephone, $sent_message);
        
                                $flightSchedule = FlightSchedule::find($scheduler->id);
                                $flightSchedule->counter = $scheduler->counter + 1;
                                $flightSchedule->save();
                            } elseif ($totalDuration2 == 0) {

                                $sms = new TransferSms();
                                $sms->sendSMS($scheduler->userDetails->mobileTelephone, $sent_message);
        
                                $flightSchedule = FlightSchedule::find($scheduler->id);
                                $flightSchedule->status = 'completed';
                                $flightSchedule->save();
                            }
                        }
                    }
                }
            }
        return Command::SUCCESS;
        }
    }
}
