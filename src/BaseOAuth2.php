<?php

namespace xmyl\oauth;

use Yii;
use yii\httpclient\Client;

abstract class BaseOAuth2
{
    public $clientId;

    public $clientSecret;

    public $callbackUrl;

    public $state;

    public $accessToken;

    public $httpClient;

    public $jsonFormat = Client::FORMAT_JSON;

    public $urlencodeFormat = Client::FORMAT_URLENCODED;

    /**
     * construct方法
     * @param string $appid 应用的唯一标识
     * @param string $appSecret appid对应的密钥
     * @param string $callbackUrl 登录回调地址
     */
    public function __construct($clientId = null, $clientSecret = null, $callbackUrl = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->callbackUrl = $callbackUrl;
        $this->httpClient = new Client();
    }

    /**
     * 获取登录跳转url
     * @return string
     */
    abstract public function getAuthorizeUrl($state = null);

    /**
     * 获取AccessToken
     * @return string
     */
    abstract public function getAccessToken($code, $state = null);

    /**
     * getUserByApi
     * @return
     */
    abstract public function getUserByApi();

    public function getParams()
    {
        $params = [];
        $params['client_id'] = $this->clientId;
        $params['redirect_uri'] = $this->callbackUrl;

        return $params;
    }
}