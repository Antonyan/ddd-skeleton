<?php

namespace Infrastructure\Models;

use Symfony\Component\HttpFoundation\Request;

class RichRequest
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @return Request
     */
    public function createFromGlobals() : Request
    {
        /** @var \Symfony\Component\HttpFoundation\Request $request */
        $request = Request::createFromGlobals();

        if (!array_key_exists('content-type', $request->headers->all())){
            return $request;
        }

        if (!strpos( $request->headers->all()['content-type'][0], 'json')){
            return $request;
        }

        $content = json_decode($request->getContent(), true);

        $request->request->replace(($content ?? [] ));

        return $request;
    }
}