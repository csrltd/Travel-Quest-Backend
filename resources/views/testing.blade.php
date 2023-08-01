<?php
        // $users=App\Models\User::with(['CallType','AlertTemplate','FlightSchedule'])->get();
        // $users=App\Models\User::with('userDetails')->get();
        // echo ($users);
        // foreach ($users as $user) {
        
        // echo "{$user->id}=>{$user->email}"."<br>";
        
        $schedulers = App\Models\FlightSchedule::with(['callerType', 'alertTemplate', 'user' => with(['userDetails'])])
            ->where('status', '=', 'active')
            ->get();
        
        // $flights=;
        
        foreach ($schedulers as $scheduler) {
            // use App\TransferSms;
            // use Carbon\Carbon;
            $current_timestamp = Carbon\Carbon::now()->toDateTimeString();
            echo 'flight_number>>>' . $scheduler->flight_number . '<br>';
            echo 'ticket_number>>>' . $scheduler->ticket_number . '<br>';
            if ($scheduler->callerType) {
                echo 'caller_type>>>' . $scheduler->callerType->name . '<br>';
        
                if ($scheduler->alertTemplate) {
                    echo 'alertTemplate>>' . $scheduler->alertTemplate->body . '<br>';
                    $current_times = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $current_timestamp);
                    $departure_time = Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $scheduler->departure_time);
                    $diff_in_hours = $departure_time->diffInMinutes($current_times);
                    $remaining_times = ($scheduler->callerType->name - $scheduler->counter) * 30;
                    
                    $startTime = Carbon\Carbon::parse($current_timestamp);
    $endTime = Carbon\Carbon::parse($scheduler->departure_time);

    $totalDuration = $endTime->diffForHumans($startTime);
    $totalDuration2 = $endTime->diffInMinutes($startTime);
                    if ($scheduler->user) {
                        echo 'user>>email>>' . $scheduler->user->email . '<br>';
                        if ($scheduler->user->userDetails) {
                            $formated_time = floor($totalDuration2 / 60).'hours and '.($totalDuration2 -   floor($totalDuration2 / 60) * 60)." minutes";
                            $sent_message="Hi ".$scheduler->user->userDetails->lastName.",<br>".$scheduler->alertTemplate->title." <br> Your departure time is  ".$scheduler->departure_time."<br>"."Remaining Time is ".$formated_time."<br>".$scheduler->alertTemplate->body;
                            
                            echo "<br>".$sent_message."<br>";
                            if ($remaining_times == $diff_in_hours) {
                                // $sms = new TransferSms();
                                //         $sms->sendSMS($scheduler->user->userDetails->mobileTelephone, $sent_message);
        
                                $flightSchedule = FlightSchedule::find($scheduler->id);
                                $flightSchedule->counter = $scheduler->counter + 1;
                                $flightSchedule->save();
                            } elseif ($diff_in_hours == 0) {
                                // $sms = new TransferSms();
                                // $sms->sendSMS($scheduler->user->userDetails->mobileTelephone, $sent_message);
        
                                $flightSchedule = FlightSchedule::find($scheduler->id);
                                $flightSchedule->status = 'completed';
                                $flightSchedule->save();
                            }
                        }
                    }
                }
            }
          
    echo($totalDuration);
    echo($totalDuration2);
            echo 'CURRENTS TIMES>>' . $current_timestamp . '<br>';
            echo 'DEPARTURES TIMES>>' . $scheduler->departure_time . '<br>';
        
            echo 'DIFFERENT IN HOURS>>>' . $diff_in_hours . '<br>';
        
            echo 'user>>mobileTelephone>>' . $scheduler->user->userDetails->mobileTelephone . '<br>';

            echo "======================================================================================<br>";
        }
        
        ?>