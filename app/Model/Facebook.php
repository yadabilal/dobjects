<?php

namespace App\Model;

use Carbon\Carbon;

class Facebook extends Base
{
    const EVENT_PAGE_VIEW = 'PageView';
    const EVENT_SHOP = 'Purchase';
    const EVENT_BASKET = 'AddToCart';
    const EVENT_WISHLIST = 'AddToWishlist';
    const EVENT_SEARCH = 'Search';

    const BASE_URL = 'https://graph.facebook.com/v17.0/';

    const PIX_ID = '1253011552026497';
    const ACCESS_KEY = 'EAASUvHti5TsBO9uGSdKITCsvYUOCmKDTxfuhhkwVY4jkeSmh2rAvVjvvRqGIsaZCYCF4TT23D2x4Hhg2CZCQT7PWRSPIZBZB1AMZCulteS4ecg6IZC8gV9aPiz3RMMvYD2SOYhYyvZBX3P66pj0C2q6AeLaZAZCuL8jJDrHqeLPmU7CtZAqnfFzjhqeSEtJq5nybscvwZDZD';

    public $event = self::EVENT_PAGE_VIEW;
    public $sourceUrl = '';
    public $user = null;
    public $test = false;
    public $testValue = 'TEST53096';
    public $pixId = self::PIX_ID;
    public $accessKey = self::ACCESS_KEY;
    public $customData = [];

    function setSetting($settings){
        $this->pixId = @$settings['facebookPixId'] ?: $this->pixId;
        $this->accessKey = @$settings['facebookAccessKey'] ?: $this->accessKey;
        $this->test = @$settings['facebookTest'] ?: $this->test;
        $this->testValue = @$settings['facebookTestValue'] ?: $this->testValue;
    }

    function events($settings = null) {
        $response = null;
        try {
            $url = self::BASE_URL.self::PIX_ID.'/events';
            $userData = [];

            if($settings) {
                $this->setSetting($settings);
            }

            if($this->user) {
                $userData['ph'][] = hash('sha256', $this->user->phone);
                $userData['fn'][] = hash('sha256', $this->user->name);
                $userData['ln'][] = hash('sha256', $this->user->surname);
                $userData['external_id'][] = hash('sha256', $this->user->id);
            }

            $data = [
                "event_name"=> $this->event,
                "event_time"=> Carbon::now()->timestamp,
                "action_source"=> "website",
                "user_data" => $userData,
                "custom_data"=> [
                    $this->customData
                ],
                'event_source_url' => $this->sourceUrl
            ];

            $body =  [
                'data' => [$data],
                "access_token" => self::ACCESS_KEY,
            ];

            if($this->test) {
                $body["test_event_code"] = $this->testValue;
            }

            $ch = curl_init( $url );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $body ) );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }catch (\Exception $e) {
            $response = [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }

        return  $response;
    }
}
