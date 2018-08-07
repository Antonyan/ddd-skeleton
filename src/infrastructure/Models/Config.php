<?php
namespace Infrastructure\Models;

use Infrastructure\Exceptions\InfrastructureException;

class Config
{
    private const CONFIG_PLACEHOLDERS = 'ConfigPlaceholders';

    /**
     * @var array
     */
    private $configList = [];

    /**
     * @var array
     */
    private $dataForConfig = [];

    /**
     * @var array
     */
    private $placeholders = [
        '{Y-m}' => ['gmdate' => 'Y-m'],
        '{Y-m-d}' => ['gmdate' => 'Y-m-d'],
        '{H:i:s}' => ['gmdate' => 'H:i:s'],
        '{YmdHis}' => ['gmdate' => 'YmdHis'],
        '{process_id}' => ['getmypid' => ''],
    ];

    /**
     * Config constructor.
     * @param $config
     * @throws InfrastructureException
     */
    public function __construct($config)
    {
        if ($config === null){
            throw new InfrastructureException('You should specified config path');
        }

        $configData =[];

        if (!is_array($config)){
            $configData = $this->parseFile($config);
        }

        if (is_array($config)){
            $configData = $config;
        }

        $this->setDataForConfig($configData);
    }

    /**
     * @param $fileName
     * @return array
     */
    private function parseFile($fileName) : array
    {
        if ($this->getFileExtension($fileName) === 'ini'){
            return parse_ini_file($fileName, true);
        }

        if ($this->getFileExtension($fileName) === 'php'){
            return require $fileName;
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws InfrastructureException
     */
    public function get($name)
    {
        if (!array_key_exists($name, $this->getConfigList())){
            return $this->loadData($name);
        }
        return $this->getConfigList()[$name];
    }

    /**
     * @param $name
     * @return mixed
     * @throws InfrastructureException
     */
    private function loadData($name)
    {
        if (!array_key_exists($name, $this->dataForConfig)){
            throw new InfrastructureException('There isn\'t property ' . $name . ' in config');
        }

        $fieldValue = $this->dataForConfig[$name];

        if (is_array($fieldValue)){
            return $this->add($name, $fieldValue)->get($name);
        }

        return $this->add($name, $this->renderValue($fieldValue))->get($name);
    }

    /**
     * @param $dataValue
     * @return mixed
     * @throws InfrastructureException
     */
    private function renderValue($dataValue)
    {
        $resultValue = $dataValue;

        preg_match_all('/{.*?}/s', $dataValue, $placeholdersInValue);
        if ($placeholdersInValue[0]) {
            foreach ($placeholdersInValue[0] as $placeholderTemplate) {
                $resultValue = str_replace($placeholderTemplate, $this->getPlaceholderValue($placeholderTemplate), $resultValue);
            }
        }

        return $resultValue;
    }

    /**
     * @param $dataValue
     * @return array
     * @throws InfrastructureException
     */
    private function renderArray($dataValue) : array
    {
        $result = [];

        foreach ($dataValue as $fieldName => $value) {
            if (is_array($value)){
                $result[$fieldName] = $this->renderArray($dataValue);
                continue;
            }

            $result[$fieldName] = $this->renderValue($value);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->getConfigList();
    }

    /**
     * @param $name
     * @param $argument
     * @return mixed
     * @throws InfrastructureException
     */
    public function __call($name, $argument)
    {
        return $this->get($name);
    }

    /**
     * @param $key
     * @param $value
     * @return Config
     */
    public function add($key, $value) : Config
    {
        $this->configList[$key] = $value;
        return $this;
    }

    /**
     * @param $name
     * @return mixed
     * @throws InfrastructureException
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param Config $config
     * @return Config
     */
    public function merge(Config $config) : Config
    {
        $this->setConfigList(array_replace_recursive($this->getConfigList(), $config->getConfigList()));
        $this->placeholders = array_replace_recursive($this->placeholders, $config->getPlaceholders());
        $this->dataForConfig = array_replace_recursive($this->dataForConfig, $config->getDataForConfig());

        return $this;
    }

    /**
     * @param $fieldName
     * @return bool
     */
    public function has($fieldName) : bool
    {
        return array_key_exists($fieldName, $this->getConfigList()) || array_key_exists($fieldName, $this->getDataForConfig());
    }

    /**
     * @return array
     */
    public function getConfigList() : array
    {
        return $this->configList;
    }

    /**
     * @return string
     */
    private function getFileExtension($filename) : string
    {
        $path_info = pathinfo($filename);
        return $path_info['extension'];
    }

    /**
     * @param array $configList
     * @return Config
     */
    private function setConfigList($configList) : Config
    {
        $this->configList = $configList;

        return $this;
    }

    /**
     * @return array
     */
    private function getPlaceholders() : array
    {
        return $this->placeholders;
    }

    /**
     * @param array $placeholders
     * @return Config
     */
    private function addPlaceholders(array $placeholders) : Config
    {
        foreach ($placeholders as $name => $value) {
            $this->placeholders['{' . $name . '}'] = $value;
        }
        return $this;
    }

    /**
     * @param $templateName
     * @return mixed
     * @throws InfrastructureException
     */
    private function getPlaceholderValue($templateName)
    {
        if (!array_key_exists($templateName, $this->getPlaceholders())) {
            $this->loadPlaceholder($templateName);
        }

        $placeholderParams = $this->getPlaceholders()[$templateName];

        if (!is_array($placeholderParams)){
            return $placeholderParams;
        }

        $functionName = key($placeholderParams);

        if (!is_callable($functionName)){
            throw new InfrastructureException('Function ' . key($placeholderParams) . 'isn\'t callable');
        }

        return call_user_func_array(key($placeholderParams), array($placeholderParams[$functionName]));
    }

    /**
     * @param $templateName
     * @return Config
     * @throws InfrastructureException
     */
    private function loadPlaceholder($templateName) : Config
    {
        if (!array_key_exists(self::CONFIG_PLACEHOLDERS, $this->dataForConfig)){
            throw new InfrastructureException('Template ' . $templateName . 'doesn\'t exsist in placeholders config');
        }

        return $this->addPlaceholders($this->dataForConfig[self::CONFIG_PLACEHOLDERS]);
    }

    /**
     * @return array
     */
    public function getDataForConfig() : array
    {
        return $this->dataForConfig;
    }

    /**
     * @param array $dataForConfig
     * @return Config
     */
    private function setDataForConfig($dataForConfig) : Config
    {
        $this->dataForConfig = $dataForConfig;
        return $this;
    }
}