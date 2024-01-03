<?php

namespace App\Services;
use Twilio\Rest\Client;

/**
 * Class TwilioServices.
 */
class TwilioServices
{
    public function whatsapp_message($recipient, $message=''){
        $twilioSid = env('twilio_sid');
        $twilioToken = env('twilio_auth_token');
        $twilioWhatsAppNumber = env('twilio_whatsapp_number');
        $recipientNumber = $recipient;
        $message = $message;

        $twilio = new Client($twilioSid, $twilioToken);

        try {
            $message = $twilio->messages
            ->create("whatsapp:".$recipientNumber."",
                array(
                "from" => "whatsapp:".$twilioWhatsAppNumber."",
                "body" =>$message
                )
            );

        } catch (\Exception $e) {
            response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
