<?php
namespace App\Services;

class FcmService
{
    private static $url = 'https://fcm.googleapis.com/fcm/send';

    public static function send(string $token, string $title, string $body, string $icon, string $link)
    {
        $request_body = [
            'to'           => $token,
            'notification' => [
                'title'        => $title,
                'body'         => $body,
                'icon'         => $icon,
                'click_action' => $link
            ],
            "time_to_live" => 1000,
        ];

        $fields = json_encode($request_body);

        $request_headers = [
            'Content-Type: application/json',
            'Authorization: key=' . config('firebase.server_key'),
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }
}
