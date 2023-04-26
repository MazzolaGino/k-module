<?php

namespace KLib\Implementation;

interface ConfigurationInterface
{
    public function get(string $name): mixed;
    public function has(string $name): bool;
}
