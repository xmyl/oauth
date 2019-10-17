OAuthLogin
==========
This is a oauth2 login.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist xmyl/oauth "*"
```

or add

```
"xmyl/oauth": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

Use login

```php
$oauth = new \xmyl\oauth\GithubOAuth2($clientId, $clientSecret, $callbackUrl);
$redirectUri = $oauth->getAuthorizeUrl()
```

Use callback
```php
$oauth = new \xmyl\oauth\GithubOAuth2($clientId, $clientSecret, $callbackUrl);
$status = $oauth->getAccessToken($code);
if (!$status) {
    throw new \Exception('Access Token error');
}

$oauthUser = $oauth->getUserByApi();
```
