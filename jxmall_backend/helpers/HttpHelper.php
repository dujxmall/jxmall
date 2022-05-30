<?php
/**
 * Created by PhpStorm.
 * Author:ganxh
 * User: cp
 * Date: 2021/9/10
 * Time: 9:33
 */

namespace app\helpers;


use yii\base\InvalidConfigException;
use yii\httpclient\Client;

class HttpHelper
{

    /**
     * Created by: ganxh
     * Date: 2021/9/10
     * Time: 9:33
     * Note:
     * @param string $url
     * @param array $data
     * @return \ArrayObject|mixed
     */
    public static function get($url, $data = [], $header = [])
    {
        //
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setHeaders($header)
                ->setMethod('get')
                ->setUrl($url)
                ->setData($data)
                ->send();
        } catch (InvalidConfigException $e) {
            return false;
        }
        return SerializeHelper::decode($response->content);
    }

    public static function post($url, $data = null, $header = ['Content-Type' => 'www-form-urlencoded;charset=UTF-8'])
    {
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders($header)
                ->setMethod('post')
                ->setData($data)
                ->send();
        } catch (InvalidConfigException $e) {
            return false;
        }
        return SerializeHelper::decode($response->content);
    }

    public static function postRaw($url, $header = [], $data = null)
    {

        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        try {
            $response = $client->createRequest()
                ->setUrl($url)
                ->setHeaders($header)
                ->setMethod('post')
                ->setContent($data)
                ->send();
        } catch (InvalidConfigException $e) {
            return false;
        }
        return SerializeHelper::decode($response->content);
    }

    public static function download($url, $save_path)
    {
        $fh = fopen($save_path, 'w');
        $client = new Client([
            'transport' => 'yii\httpclient\CurlTransport'
        ]);
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->setOutputFile($fh)
            ->send();
        if ($response->statusCode != 200) {
            fclose($fh);
            throw new \Exception('保存失败!');
        }
        fclose($fh);
        return $save_path;
    }
}
