<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TWInfoCreateMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $tWInfo;
    protected $tW;
    protected $userObjectArray;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tWInfo, $tW, $userObjectArray)
    {
        //
        $this->tWInfo = $tWInfo;
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
        $tWInfo = $this->tWInfo;
        $tW = $this->tW;
        $userObjectArray = $this->userObjectArray;
        $message = $this;
        $messageSubject = "New 3W info [" . $tW->title . "]";
        
        $message = $message->subject( $messageSubject );
        $message = $message->view('mail.tw_info_create_mail')->with([
            'tWInfo' => $tWInfo,
            'tW' => $tW,
            'userObjectArray' => $userObjectArray
        ]);
        
        return $message;
    }
}
