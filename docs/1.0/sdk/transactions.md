# Transactions

## Transaction with a stored Card

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

## Transaction without Saving a Card

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

## Void a Transaction

## Recurring Transaction with a stored Card

## Cancelling a Recurring Transaction 