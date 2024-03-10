<?php

namespace App\Console\Commands;

use App\Models\Appointmentreminder;
use App\Models\Taskreminders;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Carbon\Carbon;

class Remaindercommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run task remainders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //job status: active,completed,dismissed
        $tasks = Taskreminders::where('status','active')->get();
        //$tasks = Taskreminders::all();

        foreach ($tasks as $task) {
            $taskname = $task->medicaltask?->task_name;
            $taskdesc = $task->medicaltask?->description;
            $taskpriority = $task->medicaltask?->priority;
            $taskdeadline = $task->medicaltask?->deadline;
            $phone = $task->medicaltask?->doctor?->contact_number;
            $email = $task->medicaltask?->doctor?->email;

            $reminder = $task->reminder_datetime;
            $reminder_message = $task->reminder_message;
            $reminder_sms = $task->reminder_sms;
            $reminder_email = $task->reminder_email;

            $apikeyv2 = env('mnotify_apikeyv2');
            $key = env('mnotify_key');
            $smssenderid = env('mnotify_smssenderid');

            $currentDatetime = Carbon::now();
            $taskDatetime = Carbon::parse($reminder);
            
            if ($currentDatetime->gte($taskDatetime)) {
                logger("loading");
                if ($reminder_sms) {
                    logger("loading sms");
                    $this->sendmnotify($phone,$apikeyv2,$key,$reminder_message,$smssenderid);
                }
    
                $data = [
                    'fullname' =>  $task->medicaltask?->doctor?->full_name,
                    'reminder' =>  $task->reminder_datetime,
                    'taskname' =>  $task->medicaltask?->task_name,
                    'deadline' =>  $task->medicaltask?->deadline,
                    'description' =>  $task->medicaltask?->description
                ];
    
                // if ($reminder_email) {
    
                //     FacadesMail::send('remainder',compact('data'), function($message) use($email,$taskname) {
                //         $message->to($email)
                //         ->subject($taskname);
                //         $message->from('alert@upsaclinic.com');
                //      });
                // }

                $task->status = "completed";
                $task->save();

            } else {
                continue;
            }

        }


        //send task appointment remainders
        $tasks = Appointmentreminder::where('status','active')->get();

        foreach ($tasks as $task) {
            $taskname = $task->medicaltask?->task_name;
            $taskdesc = $task->medicaltask?->description;
            $taskpriority = $task->medicaltask?->priority;
            $taskdeadline = $task->medicaltask?->deadline;
            $phone = $task->medicaltask?->doctor?->contact_number;
            $email = $task->medicaltask?->doctor?->email;

            $reminder = $task->reminder_datetime;
            $reminder_message = $task->reminder_message;
            $reminder_sms = $task->reminder_sms;
            $reminder_email = $task->reminder_email;

            $apikeyv2 = env('mnotify_apikeyv2');
            $key = env('mnotify_key');
            $smssenderid = env('mnotify_smssenderid');

            $currentDatetime = Carbon::now();
            $taskDatetime = Carbon::parse($reminder);

            
            if ($currentDatetime->gte($taskDatetime)) {
                
                if ($reminder_sms) {
                    $this->sendmnotify($phone,$apikeyv2,$key,$reminder_message,$smssenderid);
                }
    
                $data = [
                    'fullname' =>  $task->medicaltask?->doctor?->full_name,
                    'reminder' =>  $task->reminder_datetime,
                    'taskname' =>  $task->medicaltask?->task_name,
                    'deadline' =>  $task->medicaltask?->deadline,
                    'description' =>  $task->medicaltask?->description
                ];
    
                // if ($reminder_email) {
    
                //     FacadesMail::send('remainder',compact('data'), function($message) use($email,$taskname) {
                //         $message->to($email)
                //         ->subject($taskname);
                //         $message->from('alert@upsaclinic.com');
                //      });
                // }

                $task->status = "completed";
                $task->save();

            } else {
                continue;
            }

        }


    }


    public function sendmnotify($phone,$apikeyv2,$key,$reminder_message,$smssenderid)
    {	
        $endPoint = 'https://api.mnotify.com/api/sms/quick';
        $url = $endPoint.'?key='.$apikeyv2;
        $mdata = [
           'recipient' => [$phone],
           'sender' => $smssenderid,
           'message' => $reminder_message,
        ];

        //logger($url);

        $ch = curl_init();
        $headers = array();
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mdata));
        $result = curl_exec($ch);
        $data = json_decode($result,true);
        curl_close($ch);

        logger($data);
     
    }





}
