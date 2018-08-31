# Lightrail Client for PHP v2

Lightrail is a modern platform for digital account credits, gift cards, promotions, and points (to learn more, visit [Lightrail](https://www.lightrail.com/)). This is a basic library for developers to easily connect with the Lightrail API using PHP.

## Usage

### Installation

#### Composer

You can add this library as a dependency to your project using [composer](https://getcomposer.org/):
```
composer require lightrail/lightrail-v2
```

### Configuration

Before using this client, you'll need to configure it to use your API key. You can find this in the Lightrail web app -- go to your [account settings](https://www.lightrail.com/app/#/account/profile), then click 'API keys' and 'Generate Key.'

```php
\Lightrail\Lightrail::$apiKey = <LIGHTRAIL_API_KEY>;
```

### Shopper Tokens

If you are using our Drop-in Gift Card solution, you can use this library to generate shopper tokens for purchasing or redeeming a gift card.

Gift cards can be purchased anonymously in which case the shopper token can be generated with a contactId of `''`.

```php
\Lightrail\LightrailShopperTokenFactory::generate('');
```

When redeeming a gift card the Value that backs the gift card is attached to a Contact, thus the shopper token must be generated with the Contact's ID.

```php
\Lightrail\LightrailShopperTokenFactory::generate('myContactsId');
```

Shopper tokens expire after 12 hours by default.  You can also pass in an optional second argument specifying the token's validity in seconds:

```php
\Lightrail\LightrailShopperTokenFactory::generate('myContactsId', 600);
```

## Testing

**IMPORTANT: note that several environment variables are required for the tests to run.** After cloning the repo, `composer install` dependencies, then copy `.env.example` to `.env` and fill in the following (or use your preferred way of setting environment variables):

- ` LIGHTRAIL_API_KEY`: find this in to the Lightrail web app -- go to your [account settings](https://www.lightrail.com/app/#/account/profile), then click 'API keys' and 'Generate Key.' **Note** that for running tests, you should use a test mode key.

- `LIGHTRAIL_SHARED_SECRET`: set this to any string (used to generate shopper tokens).

- `CONTACT_ID`: the Lightrail-generated contactId for the same contact.

Then you can run `composer test`.

## Contributing

Bug reports and pull requests are welcome on GitHub at <https://github.com/Giftbit/lightrail-client-php-v2>.

## License

This library is available as open source under the terms of the [MIT License](http://opensource.org/licenses/MIT).
