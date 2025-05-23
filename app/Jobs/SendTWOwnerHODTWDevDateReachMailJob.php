<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use App\Mail\TWOwnerHODTWDevDateReachMail;
use App\User;
use App\TW;
use App\UserEscalateOff;

class SendTWOwnerHODTWDevDateReachMailJob implements ShouldQueue
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
            
            $tWUserObj = new User();
            $tWUserObj->mail = $toUser->own_user;
            $tWUserObj = $tWUserObj->getUser();
            //$managerObj = $tWUserObj->getManager();
            $UserEscalateOffObj = new UserEscalateOff();
            $UserEscalateOffObj = $UserEscalateOffObj->where('user_pk', '=', $tWUserObj->mail);
            $UserEscalateOffObj = $UserEscalateOffObj->first();
            
            //array_push($toUserArray, $toUser->own_user);
            //array_push($userObjectArray_1, $tWUserObj);
            array_push($toUserArray, $tWUserObj->mail);
            
            if( ($UserEscalateOffObj) ){
                if( ($UserEscalateOffObj->email_escalate) ){
                    array_push($ccUserArray, $UserEscalateOffObj->email_escalate);
                }
            }else{
                $managerObj = $tWUserObj->getManager();
                if( ($managerObj) ){
                    //
                    /*
                    $tWUserObj_physicaldeliveryofficename = $tWUserObj->physicaldeliveryofficename;
                    $managerObj_physicaldeliveryofficename = $managerObj->physicaldeliveryofficename;
                    if( (strcasecmp($tWUserObj_physicaldeliveryofficename, $managerObj_physicaldeliveryofficename) == 0) ){
                        array_push($ccUserArray, $managerObj->mail);
                    }
                    */
                    if( ($managerObj->mail) ){
                        array_push($ccUserArray, $managerObj->mail);
                    }
                }
            }
        }
        
        //send mail
        if( isset($tW) ){
            //array_push($ccUserArray, $tW->created_user);
            //$twCreatedUser = $tW->createdUser()->mail;
            $twCreatedUser = $tW->created_user;
            
            $toUserArray = array_unique($toUserArray);
            $ccUserArray = array_unique($ccUserArray);
            
            Mail::to($toUserArray)
                //->subject("3W")
                ->cc($ccUserArray)
                //->bcc($ccUserArray)
                ->send(new TWOwnerHODTWDevDateReachMail($tW));
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
