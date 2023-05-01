<?php

namespace KLib\Manager;

use KLib\Implementation\ClassInterface;
use KLib\Implementation\PathInterface;
use KLib\Implementation\AssetManagerInterface;

/**
 * Description of PathManager
 *
 * @author mazzo
 */
class ScriptManager implements AssetManagerInterface
{
    private array $list;

    /**
     *
     * @param array $list
     */
    public function __construct(PathInterface $p, array $list)
    {
        foreach($list as $script) {
            $this->list[str_replace('.', '-', $script)] = $p->fromScript($script);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see \KLib\Implementation\AssetManagerInterface::enqueue()
     */
    public function enqueue(string $name): void
    {
        wp_enqueue_script($name, $this->list[$name]);
    }
    /**
     *
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }
}
