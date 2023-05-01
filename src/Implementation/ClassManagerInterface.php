<?php


namespace KLib\Implementation;

interface ClassManagerInterface
{
    /**
     *
     */
    public function load(): void;

    /**
     *
     * @return array
     */
    public function getList(): array;
}
