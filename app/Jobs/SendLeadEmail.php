<?php

namespace App\Jobs;

use App\Mail\LeadWelcomeEmail;
use App\Services\NotificationService;
use App\Services\TwilioServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendLeadEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $notificationService;
    protected $twilioServices;

    /**
     * Create a new job instance.
     */
    public function __construct($details)
    {
        $this->details=$details;
        $this->notificationService=new NotificationService;
        $this->twilioServices=new TwilioServices;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        mail::to('azeemkhalidg3@gmail.com')->send(new LeadWelcomeEmail($this->details));
       $this->notificationService->send_sms4_connect($this->details['mobile'], $this->details['message']);
    //    $this->twilioServices->whatsapp_message($this->details['mobile'], "Lead Created Successfully..");

    }
}
