

# Okta JWT Verifier for PHP

This is a fork and modification of the legacy php okta library.

We've provided some improvements and simplifications for practical reasons.


## Installation
The Okta JWT Verifier can be installed through composer.

```bash
composer require okta/jwt-verifier
```

This library requires a JWT library. We currently support
[firebase/php-jwt](https://packagist.org/packages/firebase/php-jwt) version 5.2. You will have to install this or create
your own adaptor.

```bash
composer require firebase/php-jwt ^5.2
```

To create your own adaptor, just implement the `Okta/JwtVerifier/Adaptors/Adaptor` in your own class.

You will also need to install a PSR-7 compliant library. We suggest that you use `guzzlehttp/psr7` in your project.

```bash
composer require guzzlehttp/psr7
```

## Setting up the Library

To validate a JWT, you will need a few different items:

1. Your issuer URL
2. The JWT string you want to verify
3. Access to your vendor autoload file in your script.

```php
require_once("/vendor/autoload.php"); // This should be replaced with your path to your vendor/autoload.php file

// prev way of making the builder:
$jwtVerifier = (new builder here)
    ->setDiscovery(new \Okta\JwtVerifier\Discovery\Oauth) // This is not needed if using oauth.  The other option is `new \Okta\JwtVerifier\Discovery\OIDC`
    ->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt)
    ->setAudience('api://default')
    ->setClientId('{clientId}')
    ->setIssuer('https://{yourOktaDomain}.com/oauth2/default')
    ->build();
```

### Caching
It's strongly suggested to cache the keys to improve performance. You can pass an implementation of `\Psr\SimpleCache\CacheInterface`
to the Adaptor constructor.

For example, in laravel:
```php
// note: named parameters are only valid for php >= 8.0
->setAdaptor(new \Okta\JwtVerifier\Adaptors\FirebasePhpJwt(request: null, leeway: 120, cache: app('cache')->store()))
```

If using symphony, you may need to use an adaptor:
https://symfony.com/doc/current/components/cache/psr6_psr16_adapters.html

## Validating an Access Token

After you have a `$jwtVerifier` from the above section and an `access_token` from a successful sign in, or
from a `Bearer token` in the authorization header, you will need to make sure that it is still valid.
All you need to do is call the `verifyAccessToken` method (where `$jwtString` is your access token in string format).

```php
$jwt = $jwtVerifier->verifyAccessToken($jwtString);
```

This will validate your JWT for the following:

- token expiration time
- the time it was issue at
- that the token issuer matches the expected value passed into the above helper
- that the token audience matches the expected value passed into the above helper

The result from the verify method is a `Jwt` object which has a few helper methods for you:

```php
dump($jwt); //Returns instance of \Okta\JwtVerifier\JWT

dump($jwt->toJson()); // Returns Claims as JSON Object

dump($jwt->getClaims()); // Returns Claims as they come from the JWT Package used

dump($jwt->getIssuedAt()); // returns Carbon instance of issued at time
dump($jwt->getIssuedAt(false)); // returns timestamp of issued at time

dump($jwt->getExpirationTime()); //returns Carbon instance of Expiration Time
dump($jwt->getExpirationTime(false)); //returns timestamp of Expiration Time
```

## Validating an Id Token

```php
$jwt = $jwtVerifier->verifyIdToken($jwtString);
```

This will validate your JWT for the following:

- token expiration time
- the time it was issue at
- that the token issuer matches the expected value passed into the above helper
- that the token audience matches the expected value passed into the above helper

The result from the verify method is a `Jwt` object which has a few helper methods for you:

```php
dump($jwt); //Returns instance of \Okta\JwtVerifier\JWT

dump($jwt->toJson()); // Returns Claims as JSON Object

dump($jwt->getClaims()); // Returns Claims as they come from the JWT Package used

dump($jwt->getIssuedAt()); // returns Carbon instance of issued at time
dump($jwt->getIssuedAt(false)); // returns timestamp of issued at time

dump($jwt->getExpirationTime()); //returns Carbon instance of Expiration Time
dump($jwt->getExpirationTime(false)); //returns timestamp of Expiration Time
```
## Need help?

If you run into problems using the SDK, you can

* Ask questions on the [Okta Developer Forums][devforum]
* Post [issues][github-issues] here on GitHub
* [Working With OAuth 2.0 Tokens](https://developer.okta.com/authentication-guide/tokens/)

## Conclusion

The above are the basic steps for verifying an access token locally. The steps are not tied directly to a framework so
you could plug in the `okta/okta-jwt` into the framework of your choice.


[devforum]: https://devforum.okta.com/
[lang-landing]: https://developer.okta.com/code/php/
[github-issues]: /okta/okta-jwt-verifier-php/issues
[github-releases]: /okta/okta-jwt-verifier-php/releases
