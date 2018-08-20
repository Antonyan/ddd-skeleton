<?php
namespace Helper;

use Exception;

class RandomGeneratorHelper extends \Codeception\Module
{
    private $increments = [];

    /**
     * @return string
     */
    public function randomHash() : string
    {
        return (hash('md5', openssl_random_pseudo_bytes(32)));
    }

    /**
     * @return int
     * @throws Exception
     */
    public function randomId() : int
    {
        return random_int(1000,100000);
    }

    /**
     * @param $type
     * @return int|mixed
     */
    public function increment($type) : int
    {
        if (!array_key_exists($type, $this->increments)){
            $this->increments[$type] = 1;
            return 1;
        }

        return ++$this->increments[$type];
    }
}