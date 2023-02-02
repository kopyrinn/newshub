<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class Util
{
    public static function getRecommendations()
    {
        if (!auth()->guest()) {
            $recommendations = auth()->user()->getRecommendations();
        } else {
            $recommendations = Cache::remember('recommendations', 1, function () {
                return User::select('users.*')
                    ->join('role_user', 'role_user.user_id', 'users.id')
                    ->join('roles', 'roles.id', 'role_user.role_id')
                    ->where('roles.slug', 'press')
                    ->whereNotNull('users.name')
                    ->whereNotNull('users.email_verified_at')
                    ->inRandomOrder()
                    ->take(3)
                    ->get();
            });
        }

        return $recommendations;
    }

    public static function getMoneyRates(){
        $money_rate = null;

        try {
            //$url = "http://www.nationalbank.kz/rss/rates_all.xml";
            $url = "https://newshub.kz/assets/rates_all.xml";
            $dataObj = simplexml_load_file($url);

            $money_rate = array();
            if ($dataObj){
                foreach ($dataObj->channel->item as $item){
                    if($item->title == 'USD'){
                        $money_rate[0]['title'] = $item->title;
                        $money_rate[0]['description'] = $item->description;
                    }
                    else if($item->title == 'EUR'){
                        $money_rate[1]['title'] = $item->title;
                        $money_rate[1]['description'] = $item->description;
                    }
                    else if($item->title == 'RUB'){
                        $money_rate[2]['title'] = $item->title;
                        $money_rate[2]['description'] = $item->description;
                    }
                    else if($item->title == 'CNY'){
                        $money_rate[3]['title'] = $item->title;
                        $money_rate[3]['description'] = $item->description;
                    }
                }
            }
        }
        catch(Exception $e){

        }
        return $money_rate;
    }
}