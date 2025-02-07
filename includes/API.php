<?php

namespace Fau\DegreeProgram\Shares;

class API
{
    private string $api;

    public function __construct() {
        $this->api = 'https://api.fau.de/pub/v1/edu/';
    }

    public function getShares($subject, $degree)
    {
        $endpoint = 'subjectShare';
        $query = [
            'lq' => rawurlencode('degree=' . $degree . '&subject=' . $subject),
            'sort' => rawurlencode('percent=-1'),
        ];

        return $this->getData($endpoint, $query);
    }

    public function getSubjects($key = '', $searchTerm = '') {
        $endpoint = 'subjects';
        $query = [
            'sort' => rawurlencode('name=1'),
        ];
        if (!empty($key)) {
            $query['lq'] = rawurlencode('campo_key=' . (int)$key);
        }
        if (!empty($searchTerm)) {
            $query['q'] = rawurlencode($searchTerm);
        }
        return $this->getData($endpoint, $query);

    }

    public function getDegrees($key = '', $searchTerm = '') {
        $endpoint = 'degrees';
        $query = [
            'sort' => rawurlencode('name=1'),
        ];
        if (!empty($key)) {
            $query['lq'] = rawurlencode('campo_key=' . (int)$key);
        }
        if (!empty($searchTerm)) {
            $query['q'] = rawurlencode($searchTerm);
        }
        return $this->getData($endpoint, $query);
    }

    private function getData($endpoint, $query = [])
    {
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Api-Key' => $this->getApiKey(),
            ]
        ];
        $queryDefault = [
            'limit' => 100, // Endpoint maximum
            'page' => 1,
        ];

        $query = array_merge($queryDefault, $query);

        $transientName = 'fau-shares_'.hash('md5', $endpoint . '_'. implode('_', $query));

        if ( false === ( $value = get_transient( $transientName ) ) ) {
            $remaining = 999;
            $page = 1;
            $data = [];
            while ($remaining > 0) {
                $query['page'] = $page;
                $apiRequest = add_query_arg($query, $this->api . $endpoint);
                $apiResponse = wp_remote_get($apiRequest, $args);
                if ( is_array( $apiResponse ) && ! is_wp_error( $apiResponse ) && $apiResponse['response']['code'] == 200) {
                    $body = json_decode($apiResponse['body'], true);
                    if (!empty($body['data'])) {
                        $data = array_merge($data, $body['data']);
                    }
                    $remaining = $body['pagination']['remaining'] ?? 0;
                    $page ++;
                } else {
                    $remaining = 0;
                }
            }
            set_transient( $transientName, $data, DAY_IN_SECONDS );
            return $data;
        } else {
            return json_decode( $value );
        }
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