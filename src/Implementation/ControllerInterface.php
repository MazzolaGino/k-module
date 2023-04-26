<?php

namespace KLib\Implementation;
use KLib\App;

interface ControllerInterface {
    public function getApp(): App;
    public function getControllerName(): string;
}