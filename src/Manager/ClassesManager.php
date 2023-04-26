<?php

namespace KLib\Manager;

use KLib\Implementation\PathInterface;
use KLib\Implementation\ConfigurationInterface;
use KLib\Implementation\ClassManagerInterface;

class ClassesManager implements ClassManagerInterface
{
    /**
     *
     * @var array
     */
    private array $list = [];

    /**
     *
     * @param ConfigurationInterface $config
     * @param PathInterface $path
     * @param array $list
     */
    public function __construct(PathInterface $p, array $list)
    {
        foreach ($list as $class) {
            $this->list[] = $p->fromClass($class);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ClassManagerInterface::getList()
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ClassManagerInterface::load()
     */
    public function load(): void
    {
        foreach($this->list as $class) {
            require_once $class;
        }
    }
}
