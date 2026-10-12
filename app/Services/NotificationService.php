<?php

namespace App\Services;

use App\Models\BusinessSetting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class NotificationService.
 */
class NotificationService
{
    public function send_notification($data, $user_id)
    {
        $user = User::find($user_id);
        if (!$user || empty($user->device_token)) {
            Log::info('FCM skipped: missing device token', ['user_id' => $user_id]);
            return false;
        }

        $title = $data['title'] ?? 'Notification';
        $body = $data['body'] ?? '';
        $clickAction = $data['click_action'] ?? url('/');
        $payloadData = array_merge([
            'title' => $title,
            'body' => $body,
            'click_action' => $clickAction,
            'url' => $clickAction,
        ], $data['data'] ?? []);

        $stringData = [];
        foreach ($payloadData as $key => $value) {
            $stringData[(string) $key] = (string) $value;
        }

        $accessToken = $this->firebaseAccessToken();
        if ($accessToken) {
            $projectId = $this->firebaseProjectId();
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $user->device_token,
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => $stringData,
                        'webpush' => [
                            'fcm_options' => [
                                'link' => $clickAction,
                            ],
                            'notification' => [
                                'title' => $title,
                                'body' => $body,
                                'icon' => $data['icon'] ?? url('dist/img/AdminLTELogo.png'),
                                'requireInteraction' => true,
                            ],
                            'headers' => [
                                'Urgency' => 'high',
                            ],
                        ],
                        'android' => [
                            'priority' => 'high',
                            'notification' => [
                                'sound' => 'default',
                                'channel_id' => 'lead_alerts',
                            ],
                        ],
                        'apns' => [
                            'payload' => [
                                'aps' => [
                                    'sound' => 'default',
                                ],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('FCM v1 send failed', [
                    'user_id' => $user_id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return false;
            }

            return $response->body();
        }

        Log::warning('FCM credentials missing; using in-app reminder notification only', [
            'user_id' => $user_id,
        ]);

        return false;
    }

    private function firebaseProjectId(): string
    {
        $credentials = $this->firebaseCredentials();
        return $credentials['project_id'] ?? env('FIREBASE_PROJECT_ID', 'tour-vision-5a892');
    }

    private function firebaseCredentials(): ?array
    {
        $path = env('FIREBASE_CREDENTIALS', storage_path('app/firebase-credentials.json'));
        if (!is_file($path)) {
            return null;
        }

        $json = json_decode((string) file_get_contents($path), true);
        if (!is_array($json) || empty($json['private_key']) || empty($json['client_email'])) {
            return null;
        }

        return $json;
    }

    private function firebaseAccessToken(): ?string
    {
        $cached = Cache::get('firebase_fcm_access_token');
        if (is_string($cached) && $cached !== '') {
            return $cached;
        }

        $credentials = $this->firebaseCredentials();
        if (!$credentials) {
            return null;
        }

        $now = time();
        $header = $this->base64UrlEncode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        $claim = $this->base64UrlEncode(json_encode([
            'iss' => $credentials['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => $credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ]));

        $unsigned = $header . '.' . $claim;
        $signature = '';
        $ok = openssl_sign($unsigned, $signature, $credentials['private_key'], 'sha256WithRSAEncryption');
        if (!$ok) {
            Log::warning('FCM JWT signing failed');
            return null;
        }

        $jwt = $unsigned . '.' . $this->base64UrlEncode($signature);
        $response = Http::asForm()->post($credentials['token_uri'] ?? 'https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->failed() || empty($response->json('access_token'))) {
            Log::warning('FCM OAuth token failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $token = $response->json('access_token');
        Cache::put('firebase_fcm_access_token', $token, now()->addMinutes(50));

        return $token;
    }

    private function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    /**send message to client agains sms 4 connect */
    public function send_sms4_connect($mobile,$message){
        $mobile=ltrim($mobile,'+');
        $mobile=$mobile;
        $type = "xml";
        $businessName = optional(BusinessSetting::first())->business_name ?: config('app.name');
        $id = env('SMS4_ID', '');
        $pass = env('SMS4_PASS', '');
        $mask = env('SMS4_MASK') ?: $businessName;
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
        $xml=simplexml_load_string($result);
        // $xml=simplexml_load_string($result) or die("Error: Cannot create object");
    }
}
