<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Contracts;

abstract class Arrayable
{
    public abstract function fromArray(array $data);

    public abstract function toArray(): array;
}