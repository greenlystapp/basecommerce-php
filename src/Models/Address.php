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

    const TYPE_BILLING = 'BILLING';
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
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $addressLine1
     */
    public function setAddressLine1($addressLine1): void
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @param mixed $addressLine2
     */
    public function setAddressLine2($addressLine2): void
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @param mixed $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @param $data
     * @return Address
     */
    public function fromArray(array $data): self
    {
        $instance = new static();

        $instance->setName(array_get($data, 'address_name'));
        $instance->setAddressLine1(array_get($data, 'address_line1'));
        $instance->setAddressLine2(array_get($data, 'address_line2'));
        $instance->setCity(array_get($data, 'address_city'));
        $instance->setState(array_get($data, 'address_state'));
        $instance->setZipCode(array_get($data, 'address_zipcode'));
        $instance->setCountry(array_get($data, 'address_country'));

        return $instance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return clear_array([
            'address_name' => $this->getName(),
            'address_line1' => $this->getAddressLine1(),
            'address_line2' => $this->getAddressLine2(),
            'address_city' => $this->getCity(),
            'address_state' => $this->getState(),
            'address_zipcode' => $this->getZipCode(),
            'address_country' => $this->getCountry()
        ]);
    }
}