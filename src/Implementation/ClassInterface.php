<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */

namespace KLib\Implementation;

interface ClassInterface
{
    /**
     *
     * @return string
     */
    public function getName(): string;
    public function getValue(): string;
    public function setName(string $name): void;
    public function setValue(string $value): void;
}
