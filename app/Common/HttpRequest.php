<?php
/**
 * Created by PhpStorm.
 * User: LX
 * Date: 17/4/18
 * Time: 下午12:45
 */

namespace App\Common;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;



class HttpRequest
{
    public static function get($url)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('GET', $url);
        $body = json_decode((string)$response->getBody(), true);
        return $body;
    }


    public static function post($url, $options)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('POST', $url, ['json' => $options]);
        $body = json_decode((string)$response->getBody(), true);
        return $body;
    }


    public static function put($url, $options)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('PUT', $url, ['json' => $options]);
        $body = json_decode((string)$response->getBody(), true);
        return $body;
    }


    public static function delete($url)
    {
        $httpClient = new \GuzzleHttp\Client();
        $response = $httpClient->request('DELETE', $url);
        $body = json_decode((string)$response->getBody(), true);
        return $body;
    }


    public static function requestAsync($mothed, $url, $options = [])
    {

        $client = new Client();
        $promise = $client->requestAsync($mothed, $url, ['json' => ['data' => $options]]);

        return $promise;
    }


    /**批量请求接口
     * @param   array $requestOptions
     * @return array
     */
    public static function batchRequest($requestOptions)
    {

        $client = new Client();

        $promises = [];
        $ret = [];

        try {

            foreach ($requestOptions as $k => $request) {
                // 根据GET请求不需要传json
                if ($request['method']== 'GET') {

                    $promises[$k] = $client->requestAsync($request['method'], $request['url']);
                }else{
                    $promises[$k] = $client->requestAsync($request['method'],$request['url'], ['json' => ['data' => $request['options'] ]]);
                }
            }

            $result = \GuzzleHttp\Promise\unwrap($promises);

            foreach ($result as $k => $item) {

                $retItem = json_decode((string)$item->getBody());

                $ret[$k] = $retItem;

            }

        } catch (RequestException $e) {
            jsonOut(500, $e->getResponse());
        }

        return $ret;

    }



}