<?php

namespace xmyl\oauth;

class WeiboOAuth2 extends BaseOAuth2
{
    private $_authUrl = 'https://api.weibo.com/oauth2/authorize';

    private $_tokenUrl = 'https://api.weibo.com/oauth2/access_token';

    private $_apiBaseUrl = 'https://api.weibo.com';

    public $uid;

    /**
     * {@inheritdoc}
     * @param  string $state
     * @return string
     */
    public function getAuthorizeUrl($state = null, $response_type = 'code', $display = null)
    {
        $params = $this->getParams();
        $params['state'] = $state;
        $params['response_type'] = $response_type;
        $params['display'] = $display;

        return $this->_authUrl . '?' . http_build_query($params);
    }

    /**
     * {@inheritdoc}
     * @param  string $code  [description]
     * @param  string $state [description]
     * @return string
     */
    public function getAccessToken($code, $state = null)
    {
        $params = $this->getParams();
        $params['client_secret'] = $this->clientSecret;
        $params['code'] = $code;
        $params['grant_type'] = 'authorization_code';

        $response = $this->httpClient->createRequest()
                ->setUrl($this->_tokenUrl)
                ->setMethod('POST')
                ->setFormat($this->urlencodeFormat)
                ->setData($params)
                ->send();

        $data = $response->getData();
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            $this->uid = $data['uid'];
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public function getUserByApi()
    {
        $response = $this->httpClient->createRequest()
                ->setUrl($this->_apiBaseUrl . '/2/users/show.json')
                ->setMethod('GET')
                ->setData([
                    'access_token' => $this->accessToken,
                    'uid' => $this->uid,
                ])
                ->send();

        $data = $response->getData();
        if (isset($data['id'])) {
            return $data;
        }

        return false;
    }
}