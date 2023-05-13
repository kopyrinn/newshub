<?php

namespace App\Helpers;

use Carbon\Carbon;

class Format
{
    public static function thumb($image, $w, $h)
    {
        // try {
        //     // $thumb = \Thumbnail::src("/{$image}", 'public')->crop($w, $h)->url(true);
        //     // return $thumb;
        // } catch (\Exception $e) {
        //     // return asset("uploads/image/img_placeholder.svg");
        // }
        return asset("storage/{$image}");
    }

    public static function num(int $number)
    {
        if ($number >= 1E9) {
            return round($number / 1E9, 2) . 'B';
        } else if ($number >= 1E6) {
            return round($number / 1E6, 2) . 'M';
        } else if ($number >= 1E3) {
            return round($number / 1E3, 2) . 'K';
        }

        return $number;
    }

    public static function date(Carbon $date)
    {
        if ($date->isToday()) {
            return __('Today') . ', ' . $date->format('H:i');
        } else {
            if (date('Y') == $date->format('Y')) {
                return \Date::parse($date)->format('j F, H:i');
            } else {
                return \Date::parse($date)->format('j F Y, H:i');
            }
        }
    }

    public static function mb_ucfirst($string, $encoding)
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}