<?php

namespace Infrastructure\Listeners;

use Infrastructure\Events\ResponseEvent;

class ResponseListener
{
    public function onResponse(ResponseEvent $event) : void
    {
        print_r("\n\n");
        print_r($event->getResponse()->getContent());
        print_r("\n\n");
        die;
    }
}