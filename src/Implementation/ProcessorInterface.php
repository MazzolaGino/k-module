<?php

namespace KLib\Implementation;

use KLib\App;

interface ProcessorInterface {
    public function getApp(): App;
    public function getProcessorName(): string;
    public function init(): void;
}