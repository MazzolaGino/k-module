<?php

namespace KLib\Implementation;

interface AssetManagerInterface
{
    /**
     * 
     *
     * @param string $name
     * @return void
     */
    public function enqueue(string $name): void;
}
