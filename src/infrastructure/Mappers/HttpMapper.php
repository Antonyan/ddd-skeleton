<?php
namespace Infrastructure\Mappers;

use Modules\LibraryModule\Exceptions\EnergonException;
use Modules\LibraryModule\Models\ArraySerializable;
use Modules\LibraryModule\Models\Collection;
use Modules\LibraryModule\Models\PaginationCollection;
use Modules\LibraryModule\Services\HttpClient;

abstract class HttpMapper extends BaseMapper
{
    const LOAD_URL = 'loadUrl';
    const GET_URL = 'getUrl';
    const CREATE_URL = 'createUrl';
    const UPDATE_URL = 'updateUrl';
    const DELETE_URL = 'deleteUrl';

    const LINKS_FIELD = 'links';

    /**
     * @var HttpClient
     */
    private $httpClient = null;

    /**
     * @var array
     */
    private $httpMapperConfig = null;

    public function __construct(array $httpMapperConfig, HttpClient $httpClient)
    {
        $this->setHttpMapperConfig($httpMapperConfig)->setHttpClient($httpClient);
    }

    /**
     * @param $conditions
     * @return Collection
     * @throws EnergonException
     */
    public function load($conditions)
    {
        return $this->sendHttpRequest('GET', $this->getHttpMapperConfig(self::LOAD_URL), ['query' => $conditions]);
    }

    /**
     * @param $identifierValue
     * @return ArraySerializable
     * @throws EnergonException
     */
    public function get($identifierValue)
    {
        return $this->sendHttpRequest('GET', $this->getUrlWithIdentifier($identifierValue, self::GET_URL), []);
    }

    /**
     * @param $identifierValue
     * @return bool
     * @throws EnergonException
     */
    public function delete($identifierValue)
    {
        $this->sendHttpRequest('DELETE', $this->getUrlWithIdentifier($identifierValue, self::DELETE_URL), []);
        return true;
    }

    /**
     * @param ArraySerializable $object
     * @return ArraySerializable
     * @throws EnergonException
     */
    public function create(ArraySerializable $object)
    {
        return $this->sendHttpRequest('POST',
            $this->getHttpMapperConfig(self::CREATE_URL),
            ['json' => $object->toArray()]
        );
    }

    /**
     * @param ArraySerializable $object
     * @param $identifierName
     * @return ArraySerializable
     * @throws EnergonException
     */
    public function update(ArraySerializable $object, $identifierName)
    {
        return $this->sendHttpRequest('PUT',
            $this->getUrlWithObjectAndIdentifier($object, $identifierName, self::UPDATE_URL),
            ['json' => $object->toArray()]
        );
    }

    /**
     * @param ArraySerializable $object
     * @param $identifierName
     * @throws EnergonException
     * @return ArraySerializable
     */
    public function updatePatch(ArraySerializable $object, $identifierName)
    {
        return $this->sendHttpRequest('PATCH',
            $this->getUrlWithObjectAndIdentifier($object, $identifierName, self::UPDATE_URL),
            ['json' => $object->toArray()]
        );
    }

    /**
     * @param $action
     * @param $url
     * @param $options
     * @return ArraySerializable|\Modules\LibraryModule\Models\Collection|void
     * @throws EnergonException
     */
    protected function sendHttpRequest($action, $url, $options)
    {
        $content = $this->getHttpClient()->sendHttpRequest($action, $url, $options)->getBody();
        
        if($content === null){
            return true;
        }

        if(array_key_exists('items', $content)){
            return $this->buildPaginationCollection($content);
        }

        return $this->buildObject($content);
    }

    /**
     * @deprecated
     * @param $action
     * @param $url
     * @param $options
     * @return ArraySerializable|\Modules\LibraryModule\Models\Collection|void
     * @throws EnergonException
     */
    protected function sendHttpRequestForGetStatus($action, $url, $options)
    {
        return $this->getHttpClient()->sendHttpRequestForGetStatus($action, $url, $options);
    }

    /**
     * @param ArraySerializable $object
     * @param $identifierName
     * @param $urlName
     * @return string
     */
    protected function getUrlWithObjectAndIdentifier(ArraySerializable $object, $identifierName, $urlName)
    {
        $identifier = $object->toArray()[$identifierName];
        return vsprintf($this->getHttpMapperConfig($urlName), [$identifier]);
    }

    /**
     * @param $identifierValue
     * @param $url
     * @return string
     */
    private function getUrlWithIdentifier($identifierValue, $url)
    {
        $urlTemplate = $this->getHttpMapperConfig($url);
        return vsprintf($urlTemplate, [$identifierValue]);
    }

    /**
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClient $httpClient
     * @return HttpMapper
     */
    private function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;

        return $this;
    }

    /**
     * @param $urlName
     * @return mixed
     */
    protected function getHttpMapperConfig($urlName)
    {
        return $this->httpMapperConfig[$urlName];
    }

    /**
     * @param array $httpMapperConfig
     * @return HttpMapper
     */
    private function setHttpMapperConfig($httpMapperConfig)
    {
        $this->httpMapperConfig = $httpMapperConfig;

        return $this;
    }

    /**
     * @param array $objectsParams
     * @return PaginationCollection
     */
    protected function buildPaginationCollection(array $objectsParams)
    {
        $collection = new PaginationCollection();
        $collection->setTotalResult(array_key_exists(
            PaginationCollection::TOTAL_RESULTS, $objectsParams) ? $objectsParams[PaginationCollection::TOTAL_RESULTS] : 0
        );
        $collection->setLimit(array_key_exists(
            PaginationCollection::LIMIT, $objectsParams) ? $objectsParams[PaginationCollection::LIMIT] : 0
        );
        $collection->setOffset(array_key_exists(
            PaginationCollection::OFFSET, $objectsParams) ? $objectsParams[PaginationCollection::OFFSET] : 0
        );
        foreach ($objectsParams['items'] as $objectParams) {
            $collection->push($this->buildObjectOptionalFields($objectParams));
        }

        return $collection;
    }
}