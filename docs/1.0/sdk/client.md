# Client

Each request to the Base Commerce Platform begins by initializing the Client object with your `SDK Username`, `SDK Password`, and `SDK Key`.

To retrieve your SDK credentials, go to [BaseCommerce Sandbox Environment](https://my.basecommercesandbox.com) or [BaseCommerce Production Environment](https://my.basecommerce.com) and the credentials are under the **Development** tab.

```php
use Greenlyst\BaseCommerce\Client;

$client = new Client(
        'sdk_username',
        'sdk_password',
        'sdk_key',
        'is_production[true|false]'
);
``` 

## Validating Credentials
You can validate credentials using the `validateCredentials` method on the Client object to check if your credentials are correct.
Here is an example of how to validate your credentials.

```php

use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\ClientException;

$client = new Client('sdk_username','sdk_password','sdk_key');

try {
    $client->validateCredentials();

} catch (ClientException $exception) {

    switch ($exception->getMessage()) {
        
        case ClientException::INVALID_CREDENTIALS:
            //Handle Stuff
            break;
        case ClientException::INVALID_URL_OR_HOST_OFFLINE:
            //Handle Stuff
            break;
    }
} 
```

## Debugging and Support

Every request made through the SDK is retrieves a unique Session ID from the request.  This Session ID can be stored after every request while testing and passed to the BaseCommerce Development  on the Support Tickets you open when running into issues. 
The Session ID will enable the BaseCommerce Development Support Team to quickly identify your connection and what went wrong.
Here is an example of how to retrieve the Session Id for the request.

```php
use Greenlyst\BaseCommerce\Client;

$client = new Client('sdk_username','sdk_password','sdk_key');

$client->validateCredentials();

$sessionId = $client->getSessionId();
```