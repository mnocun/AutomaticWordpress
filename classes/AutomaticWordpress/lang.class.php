<?php

namespace AutomaticWordpress;

use Exception;

class Lang
{
    const EN = '';
    const PL = 'pl';
    const DE = 'de';
    const ES = 'es';
    const RU = 'ru';

    protected static $langCodes = [
        Lang::EN => '',
        Lang::PL => 'pl_PL',
        Lang::DE => 'de_DE',
        Lang::ES => 'es_ES',
        Lang::RU => 'ru_RU'
    ];

    public static function getCode(string $lang) : string
    {
        return self::$langCodes[$lang] ?? Lang::EN;
    }

    public static function resolveLang($lang) : string
    {
        if (is_null($lang) || !in_array($lang, array_keys(self::$langCodes))) {
            return Lang::EN;
        }else {
            return $lang;
        }
    }
}