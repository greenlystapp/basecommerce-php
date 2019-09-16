<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Core;

use Exception;

final class TripleDESService
{
    private $io_key;

    /**
     * Creates a new TripleDESService using the provided HEX encoded String which will be converted back to bytes and used to generate the Key
     *
     * @param $key string HEX encoded string to create Key from
     */
    public function __construct($key)
    {
        if (function_exists('hex2bin')) {
            $this->io_key = hex2bin($key);
        } else {
            $sbin = "";
            for ($i = 0; $i < strlen($key); $i += 2) {
                $sbin .= pack("H*", substr($key, $i, 2));
            }
            $this->io_key = $sbin;
        }
    }

    /**
     * Encrypts the plain text that is provided using the Key this TripleDESService was initialized with
     *
     * @param string The plain text to be encrypted
     *
     * @return string The cipher text resulting from the encryption of the plain text
     */
    public function encrypt($input)
    {
        $data = openssl_encrypt($input, 'DES-EDE3', $this->io_key, OPENSSL_RAW_DATA, '');
        $data = $this->txtsec2binsec($data);
        $data = $this->binsec2hexsec($data);
        $data = urlencode($data);
        return $data;
    }

    /**
     * Decrypts the cipher text that is provided using the Key this TripleDESService was initialized with
     *
     * @param string Cipher text the cipher text to be decrypted
     *
     * @return string The plain text resulting from the decryption of the cipher text
     */
    public function decrypt($input)
    {
        $input = $this->hexsec2binsec($input);
        $input = $this->binsec2txtsec($input);
        $decrypted_data = openssl_decrypt($input, 'DES-EDE3', $this->io_key, OPENSSL_RAW_DATA, '');
        return trim(chop($decrypted_data));
    }

    /**
     * @param $binsec string
     *
     * @return string
     */
    private function binsec2txtsec($binsec): string
    {
        $data = '';
        for ($i = 0; $i < strlen($binsec); $i += 8) {
            $bin = substr($binsec, $i, 8);
            $data .= chr(bindec($bin));
        }
        return $data;
    }

    /**
     * @param $txtsec string
     *
     * @return string
     */
    private function txtsec2binsec($txtsec): string
    {
        $data = '';
        for ($i = 0; $i < strlen($txtsec); $i++) {
            $mybyte = decbin(ord($txtsec[$i]));
            $MyBitSec = substr("00000000", 0, 8 - strlen($mybyte)) . $mybyte;
            $data .= $MyBitSec;
        }
        return $data;
    }

    /**
     * @param $binsec string
     *
     * @return string
     */
    private function binsec2hexsec($binsec): string
    {
        $data = '';
        for ($i = 0; $i < strlen($binsec); $i += 8) {
            $bin = substr($binsec, $i, 8);
            $hex = dechex(bindec($bin));
            $hex = substr("00", 0, 2 - strlen($hex)) . $hex;
            $data .= $hex;
        }
        return $data;
    }

    /**
     * @param $hexsec string
     *
     * @return string
     */
    private function hexsec2binsec($hexsec): string
    {
        $data = '';
        for ($i = 0; $i < strlen($hexsec); $i += 2) {
            $byte = decbin(hexdec($hexsec[$i] . $hexsec[$i + 1]));
            $bitSec = substr("00000000", 0, 8 - strlen($byte)) . $byte;
            $data .= $bitSec;
        }
        return $data;
    }
}
