<?php

namespace App\Services;
use App\Models\User;

/**
 * Class NotificationService.
 */
class NotificationService
{
    public function send_notification($data, $user_id){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken = User::find($user_id);


        $serverKey = 'AAAA0o1MsA0:APA91bFi32QSlwa3Lf1Wldewu_EjnWLqCnicVoYeE3xusPod9XrakFU3QU33e4B2b68OXr0ih9kd2OOZMoVEKRADPDSNJaCmyWZycuj_VDU9P-wbRtLj58qUocQfbHD3LdFCCaaipt_F'; // ADD SERVER KEY HERE PROVIDED BY FCM

        $data = [
            "registration_ids" => [$FcmToken->device_token],
            "notification" => [
                "title" => $data['title'],
                "body" => $data['body'],
            ],
        ];
        $encodedData = json_encode($data);

        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === false) {
            //die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        // FCM response
        //dd($result);
    }
    /**send message to client agains sms 4 connect */
    public function send_sms4_connect($mobile,$message){
        $mobile=ltrim($mobile,'+');
        $mobile=$mobile;
        $type = "xml";
        $id ='tourvision';
        $pass ='mazhar381';
        $mask ='TOUR VISION';
        $lang = "English";
        //text Message Code
        $to =$mobile;
        $message =$message;
        $message = urlencode($message);
        // Prepare data for POST request
        $data = "id=".$id."&pass=".$pass."&msg=".$message."&to=".$to."&lang=".$lang."&mask=".$mask."&type=".$type;
        // Send the POST request with cURL
        $ch = curl_init('http://www.sms4connect.com/api/sendsms.php/sendsms/url');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); //This is the result from SMS4CONNECT cu
        $xml=simplexml_load_string($result) or die("Error: Cannot create object");
    }
}
