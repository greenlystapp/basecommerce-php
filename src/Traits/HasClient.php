<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Traits;

use Greenlyst\BaseCommerce\Client;

trait HasClient
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }
}