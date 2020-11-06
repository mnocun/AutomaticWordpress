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

    public static function getCode(string $lang) : string
    {
        return [
            Lang::EN => '',
            Lang::PL => 'pl_PL',
            Lang::DE => 'de_DE',
            Lang::ES => 'es_ES',
            Lang::RU => 'ru_RU'
        ][$lang] ?? '';
    }
}