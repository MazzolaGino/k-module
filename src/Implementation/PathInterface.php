<?php

namespace KLib\Implementation;

interface PathInterface
{
    /**
     * 
     *
     * @param string $class
     * @return string
     */
    public function path(): string;

    /**
     * 
     *
     * @param string $class
     * @return string
     */
    public function url(): string;

    /**
     *
     * @param string $class
     * @return string
     */
    public function fromClass(string $class): string;

    /**
     *
     * @param string $env
     * @return string
     */
    public function fromEnv(string $env): string;

    /**
     *
     * @param string $script
     * @return string
     */
    public function fromScript(string $script): string;

    /**
     *
     * @param string $asset
     * @return string
     */
    public function fromAsset(string $asset): string;

    /**
     *
     * @param string $controller
     * @return string
     */
    public function fromController(string $controller): string;

    /**
     *
     * @param string $processor
     * @return string
     */
    public function fromProcessor(string $processor): string;

    /**
     *
     * @param string $controller
     * @param string $template
     * @return string
     */
    public function fromTemplate(string $controller, string $template): string;
}
