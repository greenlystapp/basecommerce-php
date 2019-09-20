# Transactions

## Authorize a Transaction

Requests that the platform obtains an authorization for the specified transaction. 
This method will only obtain an authorization code and place a hold on the funds on the cardholder's card. 
Transactions that are only authorized will not be automatically settled and deposited to your bank account.
 
::: warning
- After you have obtained a successful authorization, you should either [`CAPTURE`](#capture-transaction)  
the transaction if you want the funds settled to your Merchant Account or [`VOID`](#void-a-transaction) 
the transaction if you want to release the hold on the card.  
- A successful transaction of the `AUTH` type will result in a status of `AUTHORIZED`.

:::

### With a Store Card in Vault

```php

use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\Card;
use Greenlyst\BaseCommerce\Transaction;

// Creating the client instance
$client = new Client('sdk_username','sdk_password','sdk_key');

// Creating the Card instance
$card = new Card();

// Setting the card details
$card->setToken('self_created_token_or_one_received_from_base_commerce_goes_here');

$transaction = new Transaction();
$transaction-setAmount(5);
$transaction->setCard($card);

// Setting the $client instance to the Transaction Object
$transaction->setClient($client);

// Call the authorize() method on the Transaction Object to authorize the transaction
$instance = $transaction->authorize();
```

### With Credit Card
```php
use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\Card;
use Greenlyst\BaseCommerce\Transaction;

// Creating the client instance
$client = new Client('sdk_username','sdk_password','sdk_key');

// Creating the Card instance
$card = new Card();

// Setting the card details
$bankCard->setName('Jane Doe');
$bankCard->setCardNumber('4012888888881881');
$bankCard->setCardExpirationMonth('10');
$bankCard->setCardExpirationYear('2020');
$bankCard->setAlias('Jane Doe Card');

// Creating the Transaction instance
$transaction = new Transaction();

// Setting the $client instance to the Transaction Object
$transaction->setClient($client);

// Setting the transaction details
$transaction-setAmount(5);
$transaction->setCard($card);

// Creating a sale with the Store Card Token.
// createSale() returns an instance of the Transaction Object 
// updated with the Transaction Details
$instance = $transaction->authorize();
```

## Capture Transaction

Instructs the system to mark the specified transaction (passed on `setTransactionId()`) to be settled to the Merchant's bank account. 

::: warning
- In order to execute a `CAPTURE`, the transaction must have a transaction status of `AUTHORIZED`. 
- A successful transaction of the `CAPTURE` type will result in a status of `CAPTURED`.
:::

```php
use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\Transaction;

// Creating the client instance
$client = new Client('sdk_username','sdk_password','sdk_key');

$transaction = new Transaction();
$transaction-setAmount(5);
$transaction-setTransactionId(XXXXX);

$transaction->capture();
```

## Sale Transaction

This is the most commonly used method for processing. The SALE transaction type instructs the system 
to obtain an AUTH on the cardholder's card and mark the transaction for settlement to the Merchant's bank account. 

::: warning
A successful transaction of the `SALE` type will result in a status of `CAPTURED`.
:::

### With a Stored Card in the Vault
```php

use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\Card;
use Greenlyst\BaseCommerce\Transaction;

// Creating the client instance
$client = new Client('sdk_username','sdk_password','sdk_key');

// Creating the Card instance
$card = new Card();

// Setting the card details
$card->setToken('self_created_token_or_one_received_from_base_commerce_goes_here');

$transaction = new Transaction();
$transaction-setAmount(5);
$transaction->setCard($card);

// Setting the $client instance to the Transaction Object
$transaction->setClient($client);

// Creating a sale with the Store Card Token.
// createSale() returns an instance of the Transaction Object 
// updated with the Transaction Details
$instance = $transaction->createSale();
```

### With a Credit Card

```php
use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\Card;
use Greenlyst\BaseCommerce\Transaction;

// Creating the client instance
$client = new Client('sdk_username','sdk_password','sdk_key');

// Creating the Card instance
$card = new Card();

// Setting the card details
$bankCard->setName('Jane Doe');
$bankCard->setCardNumber('4012888888881881');
$bankCard->setCardExpirationMonth('10');
$bankCard->setCardExpirationYear('2020');
$bankCard->setAlias('Jane Doe Card');

// Creating the Transaction instance
$transaction = new Transaction();

// Setting the transaction details
$transaction-setAmount(5);
$transaction->setCard($card);

// Setting the $client instance to the Transaction Object
$transaction->setClient($client);

// Creating a sale with the Store Card Token.
// createSale() returns an instance of the Transaction Object 
// updated with the Transaction Details
$instance = $transaction->createSale();

```

## Refund a Transaction

```php

```

::: warning 
A transaction cannot be refunded unless the status of the transaction is `SETTLED`.
If the transaction status is `CAPTURED`, then the only option is to VOID the transaction
:::

## Void a Transaction

## Recurring Transaction with a stored Card

## Cancelling a Recurring Transaction 