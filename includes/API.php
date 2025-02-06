<?php

namespace Fau\DegreeProgram\Shares;

class API
{
    private string $api;

    public function __construct() {
        $this->api = 'https://api.fau.de/pub/v1/edu/subjectShare';
    }

    public function getData($subject, $degree)
    {
        $query = [
            'lq' => rawurlencode('degree=' . $degree . '&subject=' . $subject),
            'sort' => rawurlencode('percent=-1'),
        ];
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Api-Key' => $this->getApiKey(),
            ]
        ];
        $apiRequest = add_query_arg($query, $this->api);
        $apiResponse = wp_remote_get($apiRequest, $args);

        if ( is_array( $apiResponse ) && ! is_wp_error( $apiResponse ) && $apiResponse['response']['code'] == 200) {
            $body = json_decode($apiResponse['body'], true);
            return $body[ 'data' ] ?? [];
        }

        return [];
    }
    
    private function getApiKey() {
        $apiKey = '';

        $apiKey = self::getMultisiteApiKey();

        if ($apiKey == '') {
            $options = get_option('fau-degree-program-shares');
            $apiKey = $options['dip-edu-api-key'] ?? '';
        }

        return $apiKey;
    }

    private function getMultisiteApiKey() {
        return method_exists(\RRZE\Settings\Helper::class, 'getEduApiKey')
            ? \RRZE\Settings\Helper::getEduApiKey()
            : '';
    }
}