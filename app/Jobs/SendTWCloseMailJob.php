<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Exception;

use Mail;
use App\Mail\TWCloseMail;
use App\User;
use App\TW;

class SendTWCloseMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    /**
     * The queue connection that should handle the job.
     *
     * @var string
     */
    //public $connection = 'sqs';
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    //public $timeout = 120;
    /**
    * The number of seconds to wait before retrying the job.
    *
    * @var int
    */
    //public $retryAfter = 3;
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 60;
    /**
    * Delete the job if its models no longer exist.
    *
    * @var bool
    */
    public $deleteWhenMissingModels = true;
    
    /**
    * Determine the time at which the job should timeout.
    *
    * @return \DateTime
    */
    /*
    public function retryUntil()
    {
        return now()->addSeconds(5);
    }
    */

    protected $tW;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tW)
    {
        //
        $this->tW = $tW;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $tW = $this->tW;
        $twUsers = $tW->twUsers;
        
        $userObjectArray_1 = array();
        $toUserArray = array();
        $ccUserArray = array();
        $bccUserArray = array();
            
        foreach($twUsers as $key=>$value){
            $toUser = $value;
            /*
            $tWUserObj = new User();
            $tWUserObj->mail = $toUser->own_user;
            $tWUserObj = $tWUserObj->getUser();
            $managerObj = $tWUserObj->getManager();
            
            //array_push($userObjectArray_1, $tWUserObj);
            */
            array_push($ccUserArray, $toUser->own_user);
        }
        
        //send mail
        if( isset($tW) ){
            array_push($toUserArray, $tW->created_user);
            
            $toUserArray = array_unique($toUserArray);
            $ccUserArray = array_unique($ccUserArray);
            /*
            if (($key = array_search($tW->done_user, $ccUserArray)) !== false) {
                array_push($bccUserArray, $ccUserArray[$key]);
                unset($ccUserArray[$key]);
            }
            */
            $bccUserArray = array_unique($bccUserArray);
            
            Mail::to($toUserArray)
                //->subject("3W")
                ->cc($ccUserArray)
                ->bcc($bccUserArray)
                ->send(new TWCloseMail($tW));
        }
    }
    
    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    /*
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }
    */
    
}
