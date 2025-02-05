<?php

namespace Fau\DegreeProgram\Shares;

defined('ABSPATH') || exit;

class Helper {

    public static function getApiKey() {
        $apiKey = '';

        $apiKey = self::getMultisiteApiKey();

        if ($apiKey == '') {
            $jobsOptions = get_option('fau-degree-program-shares');
            $apiKey = $jobsOptions['apikey'] ?? '';
        }

        return $apiKey;
    }

    public static function getMultisiteApiKey() {
        return method_exists(\RRZE\Settings\Helper::class, 'getEduApiKey')
            ? \RRZE\Settings\Helper::getEduApiKey()
            : '';
    }

}
