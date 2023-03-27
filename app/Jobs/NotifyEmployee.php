<?php

namespace App\Jobs;

use App\Models\Employee;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class NotifyEmployee implements ShouldQueue
{
    public $tries = 3;
    public $visit;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */


    public function __construct($visitingDetails)
    {
        $this->visit = $visitingDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // notify the employee here

        $emp = Employee::query()->find($this->visit->user_id);
        $user = User::query()->find($emp->user_id);
        // The Message Is Your Visitor Has Came
        $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' .$user->phone);
    }

    public function failed(\Throwable $e)
    {
        session()->flash('Something went wrong while sending sms and mail');
    }


}
