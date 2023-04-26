<?php

namespace KLib\Implementation;

interface ProcessorManagerInterface
{
    /**
     *
     * @return void
     */
    public function load(): void;

    /**
     *
     * @return array
     */
    public function getList(): array;
}
