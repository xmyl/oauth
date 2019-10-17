<?php

namespace xmyl\oauth;

class GithubOAuth2 extends BaseOAuth2
{
    private $_authUrl = 'https://github.com/login/oauth/authorize';

    private $_tokenUrl = 'https://github.com/login/oauth/access_token';

    private $_apiBaseUrl = 'https://api.github.com';

    public $allowSignup = false;

    /**
     * {@inheritdoc}
     * @param  string $state
     * @return string
     */
    public function getAuthorizeUrl($state = null)
    {
        $params = $this->getParams();
        $params['allow_signup'] = $this->allowSignup;
        $params['state'] = $state;

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

        $response = $this->httpClient->createRequest()
                ->setUrl($this->_tokenUrl)
                ->setMethod('POST')
                ->setFormat($this->jsonFormat)
                ->setData($params)
                ->send();

        $data = $response->getData();
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
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
                ->setUrl($this->_apiBaseUrl . '/user')
                ->setMethod('GET')
                ->setHeaders([
                    'Authorization' => 'token ' . $this->accessToken,
                    'User-Agent' => 'xmyl/OAuth'
                ])
                ->send();

        $data = $response->getData();
        if (isset($data['id'])) {
            return $data;
        }

        return false;
    }
}