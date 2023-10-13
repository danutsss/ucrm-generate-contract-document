<?php

namespace App\Service;

require_once __DIR__ . "/../../vendor/autoload.php";

class CustomApi
{

    /**
     * @param string $url
     * @param string $method
     * @param array $_POST
     *
     * @return array|null
     */

    const API_URL = "https://89.33.88.34/crm/api/v1.0";
    const API_KEY = "eUTP+M5+Y3HOzaLfXl4coLvyef6otD98vTdWeRaPEuaNJcEJ5a1YKcLCG89342k1";


    public static function doRequest($url, $method = 'GET', $post = [])
    {
        $method = strtoupper($method);

        $ch = curl_init();

        curl_setopt(
            $ch,
            CURLOPT_URL,
            sprintf('%s/%s', self::API_URL, $url)
        );

        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            [
                'Content-Type: application/json',
                sprintf('X-Auth-App-Key: %s', self::API_KEY),
            ]
        );

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        } elseif ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        if (!empty($post)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch) !== 0) {
            throw new \Exception(sprintf('Eroare cURL: %s', curl_error($ch)));
        }

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($status_code >= 400) {
            $error_message = isset($response['message']) ? $response['message'] : 'Eroare API';
            throw new \Exception(sprintf('%s (status code %d) [URL: %s', $error_message, $status_code, $url));
        }

        curl_close($ch);

        return $response !== false ? json_decode($response, true) : null;
    }
}



// Setting unlimited time limit (updating lots of clients can take a long time).
set_time_limit(0);
