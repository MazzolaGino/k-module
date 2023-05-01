<?php

namespace KLib\Manager;

use KLib\Implementation\ConfigurationInterface;

class ConfigurationManager implements ConfigurationInterface
{
    /**
     *
     * @var array
     */
    private array $configuration;

    /**
     *
     * @param string $filename
     * @throws \InvalidArgumentException
     */
    public function __construct(string $filename)
    {
        $config = @file_get_contents($filename);
        if (! $config) {
            throw new \InvalidArgumentException("Unable to access the configuration file");
        }

        $configuration = json_decode($config, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Error parsing configuration file");
        }

        $this->configuration = $configuration;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ConfigurationInterface::get()
     */
    public function get(string $name): mixed
    {
        return $this->configuration[$name] ?? null;
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\ConfigurationInterface::has()
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->configuration);
    }
}
