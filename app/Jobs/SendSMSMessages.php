<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Contact;
use App\Http\Controllers\Controller;

class SendSMSMessages implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
  

    protected $member, $message;

    /**
     * Create a new job instance.
     *
     * @param Member $member
     * @param $message
     */
    public function __construct(Contact $member, $message)
    {
        $this->member = $member;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sms = new Controller();
        $sms->setTo($this->member->mobilenumber);
        $sms->message($this->message);
        $sms->send();
    }
     

}
