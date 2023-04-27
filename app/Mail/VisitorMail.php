<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitingDetail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($visitingDetail)
    {
        $this->visitingDetail = $visitingDetail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('aabdelrhim974@gmail.com' , 'Canal For Sugar')
            ->subject('Visit Accepted')
            ->view('admin.email.visitor_mail',['visitor_name' => $this->visitingDetail->visitor->name , 'visit_date' => $this->visitingDetail->checkin_at]);
        // return $this->view('view.name');
    }
}
