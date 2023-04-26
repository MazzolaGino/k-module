<?php

namespace KLib\Implementation;

interface ControllerManagerInterface
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
