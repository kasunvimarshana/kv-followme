<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TWCloseMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $tW;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tW)
    {
        //
        $this->tW = $tW;
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
        $message = $this;
        $messageSubject = "3W closed [" . $tW->title . "]";
        
        $message = $message->subject( $messageSubject );
        $message = $message->view('mail.tw_close_mail')->with([
            'tW' => $tW
        ]);
        
        return $message;
    }
}
