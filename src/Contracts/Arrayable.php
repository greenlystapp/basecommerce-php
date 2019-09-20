<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Contracts;

abstract class Arrayable
{
    abstract public function fromArray(array $data);

    abstract public function toArray(): array;
}
