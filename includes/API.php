<?php

namespace Fau\DegreeProgram\Shares;

class API
{
    private string $api;
    private array $degreesUsed;

    public function __construct() {
        $this->api = 'https://api.fau.de/pub/v1/edu/';
        $this->degreesUsed = [
            '4', //'Kirchliche Prüfung',
            '5', //'Magisterprüfung (1 Fach)',
            '8', //'Staatsexamen',
            '10', //'Ph.D.',
            '51', //'Bachelor of Arts (1 Fach)',
            '52', //'Bachelor of Arts (2 Fächer)',
            '55', //'Bachelor of Science',
            '56', //'Bachelor of Education',
            '60', //'Master of Business Administration',
            '61', //'Master of Arts',
            '62', //'Magister Legum/Master of Laws',
            '63', //'Master of Health Business Administration',
            '64', //'Master of Health and Medical Management',
            '65', //'Master of Science',
            '66', //'Master of Education',
            '69', //'Master of Marketing Management',
            '70', //'Master DBA',
            '73', //'Lehramt Realschule',
            '75', //'Lehramt Gymnasium',
            '77', //'Lehramt Berufsschule',
            '78', //'Lehramt Grundschule',
            '79', //'Lehramt Mittelschule',
            '94', //'Zusatzstudien',
            '97', //'keine Abschlussprüfung'
        ];
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
            $key = str_pad((int)$key, 3, '0', STR_PAD_LEFT);
            $query['lq'] = rawurlencode('campo_key=' . $key);
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
            $key = str_pad((int)$key, 2, '0', STR_PAD_LEFT);
            $query['lq'] = rawurlencode('campo_key=' . $key);
        }
        if (!empty($searchTerm)) {
            $query['q'] = rawurlencode($searchTerm);
        }

        $degrees = $this->getData($endpoint, $query);
        $degreesFiltered = [];
        foreach ($degrees as $degree) {
            if (isset($degree['campo_key']) && in_array($degree['campo_key'], $this->degreesUsed)) {
                $degreesFiltered[] = $degree;
            }
        }
        return $degreesFiltered;
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
        if (( false === ( $value = get_transient( $transientName ) ) ) || (is_user_logged_in())) {
            $remaining = 999;
            $page = 1;
            $data = [];
            while ($remaining > 0) {
                $query['page'] = $page;
                $apiRequest = add_query_arg($query, $this->api . $endpoint);

                $apiResponse = wp_remote_get($apiRequest, $args);
                if ( is_array( $apiResponse ) && ! is_wp_error( $apiResponse ) && $apiResponse['response']['code'] == 200) {
                    
                    $body = wp_remote_retrieve_body($apiResponse);
                    if (!empty($body)) {
                        $thisdata = json_decode($body, true);
                        if (!empty($thisdata) && (!empty($thisdata['data']))) {
                            $data = array_merge($data, $thisdata['data']);
                        }
                        $remaining = $thisdata['pagination']['remaining'] ?? 0;
                        $page ++;
                    } else {
                        $remaining = 0;
                    }
                } else {
                    $remaining = 0;
                }
            }
            // Cache only for non logged in users and if data is not empty
            if ((!is_user_logged_in()) && (!empty($data))) {
                set_transient( $transientName, $data, DAY_IN_SECONDS );
            }
            return $data;
        } else {
            // return transient value
            return $value;
        }
    }

  
    
    
    public static function isUsingNetworkKey(): bool  {
        if (is_multisite()) {
            $settingsOptions = get_site_option('rrze_settings');
            if (!empty($settingsOptions->plugins->dip_edu_api_key)) {
                return true;
            }
        }
        return false;
    }

    public static function getApiKey()  {
        if (self::isUsingNetworkKey()) {
            $settingsOptions = get_site_option('rrze_settings');
            return $settingsOptions->plugins->dip_edu_api_key;
        } else {
            $options = get_option('fau-degree-program-shares');
            return isset($options['dip-edu-api-key']) ? $options['dip-edu-api-key'] : '';
        }
    }
    
}