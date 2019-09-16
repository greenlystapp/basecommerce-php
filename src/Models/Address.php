<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use Greenlyst\BaseCommerce\Contracts\Arrayable;
use function ArrayHelpers\{array_get};

class Address extends Arrayable
{
    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $addressLine1;
    /**
     * @var
     */
    private $addressLine2;
    /**
     * @var
     */
    private $city;
    /**
     * @var
     */
    private $state;
    /**
     * @var
     */
    private $zipCode;
    /**
     * @var
     */
    private $country;

    /**
     * Address constructor.
     * @param $name
     * @param $addressLine1
     * @param $addressLine2
     * @param $city
     * @param $state
     * @param $zipCode
     * @param $country
     */
    public function __construct($name, $addressLine1, $addressLine2, $city, $state, $zipCode, $country)
    {
        $this->name = $name;
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @return mixed
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param $data
     * @return Address
     */
    public function fromArray(array $data): self
    {
        return new static(
            array_get($data, 'address_name'),
            array_get($data, 'address_line1'),
            array_get($data, 'address_line2'),
            array_get($data, 'address_city'),
            array_get($data, 'address_state'),
            array_get($data, 'address_zipcode'),
            array_get($data, 'address_country'));
    }

    /**
     * @return array
     */
    protected function toArray(): array
    {
        return [
            'address_name' => $this->getName(),
            'address_line1' => $this->getAddressLine1(),
            'address_line2' => $this->getAddressLine2(),
            'address_city' => $this->getCity(),
            'address_state' => $this->getState(),
            'address_zipcode' => $this->getZipCode(),
            'address_country' => $this->getCountry()
        ];
    }
}