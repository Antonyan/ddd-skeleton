<?php

namespace Infrastructure\Models;

use Infrastructure\Services\AssociationsSerializer;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\RuntimeException;

abstract class BaseJsonResponse extends Response
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * BaseJsonResponse constructor.
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct();
        $this->data = $data;
    }

    /**
     * @return Response
     * @throws InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     */
    public function send() : Response
    {
        $this->setContent($this->getContentWithSerializer());
        $this->setStatusCode($this->statusCode());
        $this->headers->set('Content-type', 'application/json');
        
        parent::send();

        return $this;
    }

    abstract protected function statusCode() : int;

    /**
     * @return string
     * @throws RuntimeException
     */
    private function getContentWithSerializer() : string
    {
        if (!$this->data){
            return '';
        }

        return (new AssociationsSerializer())->serialize($this->data);
    }
}