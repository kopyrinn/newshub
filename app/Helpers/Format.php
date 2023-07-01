<?php

namespace App\Helpers;

class Format
{
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

    public static function mb_ucfirst($string, $encoding)
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, null, $encoding);
        return mb_strtoupper($firstChar, $encoding) . $then;
    }
}