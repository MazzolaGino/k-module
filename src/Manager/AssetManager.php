<?php

namespace KLib\Manager;

use KLib\Implementation\PathInterface;
use KLib\Implementation\AssetManagerInterface;

class AssetManager implements AssetManagerInterface
{
    private array $list;

    /**
     *
     * @param array $list
     */
    public function __construct(PathInterface $p, array $list)
    {
        foreach ($list as $script) {
            $this->list[str_replace('.', '-', $script)] = $p->fromAsset($script);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\AssetManagerInterface::enqueue()
     */
    public function enqueue(string $name): void
    {
        wp_enqueue_style($name, $this->list[$name]);
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }



}
