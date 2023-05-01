<?php

namespace KLib\Manager;

use KLib\Implementation\PathInterface;
use KLib\Implementation\ConfigurationInterface;
use KLib\Implementation\ProcessorManagerInterface;

class ProcessManager implements ProcessorManagerInterface
{
    public const PROCESS_POSTFIX = '_processor';

    /**
     *
     * @var array
     */
    private array $list = [];

    /**
     *
     * @var ConfigurationInterface
     */
    private ConfigurationInterface $config;

    /**
     *
     * @var PathInterface
     */
    private PathInterface $path;

    /**
     *
     * @param ConfigurationInterface $config
     * @param PathInterface $path
     * @param array $list
     */
    public function __construct(ConfigurationInterface $config, PathInterface $p, array $list)
    {
        $this->config = $config;
        foreach ($list as $process) {
            $this->list[$process] = $p->fromProcessor($process);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ControllerManagerInterface::getList()
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ProcessorManagerInterface::load()
     */
    public function load(): void
    {
        foreach($this->list as $key => $processor) {
            require_once $processor;
            $processorString = $this->config->get('processor_namespace').$key;
            
            (new $processorString())->init();
        }
    }
}
