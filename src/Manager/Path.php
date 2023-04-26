<?php

namespace Klib\Manager;

use KLib\Implementation\ClassInterface;
use KLib\Implementation\PathInterface;

/**
 *
 * @author kuroxray
 *
 */
class Path implements PathInterface
{
    /**
     *
     * @var string
     */
    private string $path;

    /**
     *
     * @var string
     */
    private string $url;

    /**
     *
     * @param string $p
     * @param string $u
     */
    public function __construct(string $p, string $u)
    {
        $this->path = $p;
        $this->url = $u;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromClass()
     */
    public function fromClass(string $class): string
    {
        return $this->path . $class . '.php';
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromEnv()
     */
    public function fromEnv(string $env): string
    {
        return $this->path . 'config/' . $env . '.json';
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromScript()
     */
    public function fromScript(string $script): string
    {
        return $this->url . 'assets/js/' . $script;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromAsset()
     */
    public function fromAsset(string $asset): string
    {
        return $this->url . 'assets/css/' . $asset;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromController()
     */
    public function fromController(string $controller): string
    {
        return $this->path . 'Controller/' . $controller . '.php';
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromProcessor()
     */
    public function fromProcessor(string $processor): string
    {
        return $this->path . 'Processor/' . $processor . '.php';
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\PathInterface::fromTemplate()
     */
    public function fromTemplate(string $controller, string $template): string
    {
        return $this->path."templates/{$controller}/{$template}.php";
    }



}
