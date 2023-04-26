<?php 

require_once __DIR__ . '/vendor/autoload.php';

use KLib\Generator\PluginGenerator;

if (!isset($argv[1])) {
    echo "Please provide the name of the plugin.\n";
    exit(1);
}

$pluginName = $argv[1];

$generator = new PluginGenerator($pluginName);
$generator->generatePlugin(__DIR__);