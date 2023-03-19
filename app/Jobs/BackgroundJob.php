<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class BackgroundJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $visitingDetails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($visitingDetails)
    {
        $this->visitingDetails = $visitingDetails;
    }

    public function handle()
    {
        // sleep(10);
        $send_mail = Http::get('https://qudratech-eg.net/mail/tt.php?vid=' . $this->visitingDetails->visitor->id);
        $send_sms = Http::get('https://www.qudratech-eg.net/sms_api.php?mob=' . $this->visitingDetails->visitor->phone);
    }

    public function failed(\Throwable $e)
    {
        session()->flash('Something went wrong while sending sms and mail');
    }
}
