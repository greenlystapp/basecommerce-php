<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use Greenlyst\BaseCommerce\Client;

class BankCard
{
    /**
     * @var Client
     */
    private $client;

    private $name;



    /**
     * BankCard constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }



}