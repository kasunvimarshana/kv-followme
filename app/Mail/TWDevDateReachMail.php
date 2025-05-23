<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TWDevDateReachMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $tW;
    protected $userObjectArray;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tW, $userObjectArray)
    {
        //
        $this->tW = $tW;
        $this->userObjectArray = $userObjectArray;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        $tW = $this->tW;
        $userObjectArray = $this->userObjectArray;
        $message = $this;
        $messageSubject = "Due date reached 3W [" . $tW->title . "]";
        
        $message = $message->subject( $messageSubject );
        $message = $message->view('mail.tw_dev_date_reach_mail')->with([
            'tW' => $tW,
            'userObjectArray' => $userObjectArray
        ]);
        /*$message = $message->markdown('mail.tw_dev_date_reach_mail')->with([
            'tW' => $tW
        ]);*/
        
        return $message;
    }
}
