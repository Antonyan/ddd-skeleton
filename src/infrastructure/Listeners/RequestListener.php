<?php

namespace Infrastructure\Listeners;

use Exception;
use Infrastructure\Events\RequestEvent;
use Infrastructure\Models\ValidationRulesReader;
use Infrastructure\Models\Validator;

class RequestListener
{
    /**
     * @param RequestEvent $event
     * @throws Exception
     */
    public function onRequest(RequestEvent $event)
    {
        $validationRulesReader = new ValidationRulesReader($event->getController(), $event->getMethodName());
        $validator = new Validator($validationRulesReader->rules());
        $validator->validate(array_merge($event->getRequest()->request->all(), $event->getRequest()->query->all()));

        $this->filterRequest($event, $validationRulesReader);

        //query string preprocessor

        $queryData = $event->getRequest()->query->all();
        $processedQueryData = [];

        foreach ($queryData as $parameterName => $parameterValue) {
            $processedQueryParameter = trim($parameterValue, '()');

            if (strpos($processedQueryParameter, '|')){
                $processedQueryParameter = explode('|', $processedQueryParameter);
            }

            $processedQueryData[$parameterName] = $processedQueryParameter;
        }

        $event->getRequest()->query->replace($processedQueryData);
    }

    /**
     * @param RequestEvent $event
     * @param $validationRulesReader
     */
    private function filterRequest(RequestEvent $event, $validationRulesReader): void
    {
        $validationFields = $validationRulesReader->validationFields();
        $request = $event->getRequest();

        $request->request->replace(array_intersect_key($request->request->all(), array_flip($validationFields)));
        $request->query->replace(array_intersect_key($request->query->all(), array_flip($validationFields)));
    }
}